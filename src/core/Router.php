<?php

abstract class Router {

    private static $requestParams = [];

    private static $routes = [
        '/'                              => ['controller' => 'home',       'action' => 'index'],
        '/results'                       => ['controller' => 'results',    'action' => 'preliminary'],
        '/results/final'                 => ['controller' => 'results',    'action' => 'definitive'],
        '/results/save'                  => ['controller' => 'results',    'action' => 'save'],
        '/validation/election'           => ['controller' => 'validation', 'action' => 'election'],
        '/validation/constituencies'     => ['controller' => 'validation', 'action' => 'constituencies'],
        '/validation/constituencies/:id' => ['controller' => 'validation', 'action' => 'constituencies'],

        // when fetching from DB
        '/:slug/results'                 => ['controller' => 'results',    'action' => 'preliminary'],
        '/:slug/results/final'           => ['controller' => 'results',    'action' => 'definitive'],
        '/:slug/'                        => ['controller' => 'home',       'action' => 'index'],
    ];

    ///////////////////////////////////////////////////////////////////////////////
    /**
     * Tries to generate a URL out of parmeters
     *
     * @param  array $params Parameters for the url (controller, action, etc.)
     * @return string route URL
     */
    public static function url(array $params, bool $includeHost = false) {
        // filter out empty paremeters
        $params = array_filter($params);
        $route  = self::findRouteMatchForParams($params);
        $url    = $route;
        $prefix = $includeHost ? HOST_URL : '';

        // if there's no matching route specified, generate a default url
        if ( ! $route) {
            return $prefix . self::generateNoRouteMatchUrl($params);
        }

        // which parameters does the root expect for a complete match
        $neededParams  = self::getNeededRouteParams($route);
        $missingParams = array_diff($neededParams, array_keys($params));
        $extraParams   = array_diff(array_keys($params), $neededParams);

        if ($missingParams) {
            throw new Exception('Missing parameters: ' . implode(', ', $missingParams));
        }

        // replace the provided parameters in the matched route
        foreach ($neededParams as $param) {
            $value   = $params[$param];
            $pattern = sprintf('/:%s/', preg_quote($param));
            $url     = preg_replace($pattern, $value, $url);
        }

        // remove left slash as WEBROOT provides it
        $url  = WEBROOT . ltrim($url, '/');

        // add any extra parameters as query params
        $url .= self::generateQueryParameters(array_intersect_key($params, array_flip($extraParams))); 

        return $prefix . $url;
    }

    ///////////////////////////////////////////////////////////////////////////////
    private function findRouteMatchForParams(array $params): string {
        $essential = self::getEssentialParams($params);

        $otherProvided = array_diff($params, $essential);

        // if other params are provided, try to find a route
        // where they are all specified
        if ($otherProvided) {
            foreach (self::$routes as $route => $routeParams) {
                preg_match_all('/:([^\/]+)/', $route, $match);

                if ($match[1]) {
                    $allRouteParams    = array_merge($match[1], array_keys($routeParams));
                    $allProvidedParams = array_keys($params);
                    if (array_diff($allRouteParams, $allProvidedParams) === [] && $essential === $routeParams) {
                        return $route;
                    }
                }
            }
        }

        return array_search($essential, self::$routes);
    }

    ///////////////////////////////////////////////////////////////////////////////
    private function getEssentialParams(array $params): array {
        if ( ! isset($params['controller']) || ! $params['controller']) {
            throw new Exception('No controller provided for URL generation.');
        }

        if ( ! isset($params['action']) || ! $params['action']) {
            throw new Exception('No action provided for URL generation.');
        }

        return [
            'controller' => $params['controller'],
            'action'     => $params['action'],
        ];
    }

    ///////////////////////////////////////////////////////////////////////////////
    private function generateNoRouteMatchUrl(array $params): string {
        $essential    = self::getEssentialParams($params);
        $nonEssential = array_diff($params, $essential);
        $queryParams  = self::generateQueryParameters($nonEssential);

        // base url consist of just the controller        
        $url          = WEBROOT . $essential['controller'];

        // if the url is going to end on /index,
        // skip the action part and append just the query parameters
        $url .= ($essential['action'] === 'index')
                ? $queryParams
                : '/' . $essential['action'] . ltrim($queryParams, '/');

        return $url;
    }

    ///////////////////////////////////////////////////////////////////////////////
    private function getNeededRouteParams(string $url): array {
        // controller and action are always needed
        $needed = ['controller', 'action'];

        // check for any additional params (:param) which need to be present
        preg_match_all('/:([^\/]+)/', $url, $params);

        return array_merge($needed, $params[1]);
    }

    ///////////////////////////////////////////////////////////////////////////////
    /**
     * Generates query URL paremeters from array.
     *
     * @param  array $params
     * @return string query: /?var1=hello&var2=world
     */
    public static function generateQueryParameters(array $params): string {
        if ( ! $params) {
            return '';
        }

        return '/?' . http_build_query($params);
    }


    ///////////////////////////////////////////////////////////////////////////////
    //                            REQUEST PROCESSING                             //
    ///////////////////////////////////////////////////////////////////////////////


    ///////////////////////////////////////////////////////////////////////////////
    public static function getCurrentRequestParams(): ?array {
        $url     = self::getCurrentRequestUrl();
        $request = self::getParamsByMatchingRoute($url);

        if ($request) {
            return $request;
        }

        // if there's no matching route,
        // provide default ones: /controller/action
        $urlParts = self::splitUrl($url);

        if (count($urlParts) <= 2) {
            return [
                'controller' => $urlParts[0] ?? 'home',
                'action'     => $urlParts[1] ?? 'index'
            ];
        }

        return NULL;
    }

    ///////////////////////////////////////////////////////////////////////////////
    private function getCurrentRequestUrl() {
        // replace WEBROOT with a single / slash
        $request = preg_replace('/^(' . preg_quote(WEBROOT, '/') . ')/', '/', $_SERVER['REQUEST_URI']);

        // remove query part
        return preg_replace('/(\/?\?.*)/', '', $request);
    }

    ///////////////////////////////////////////////////////////////////////////////
    // cycle through all defined routes and check if URL matches any of them
    public static function getParamsByMatchingRoute(string $url): ?array {
        foreach (self::$routes as $route => $params) {
            // base route params are part of the current request params
            self::$requestParams = $params;

            if (self::doesUrlMatchRoute($url, $route)) {
                return self::$requestParams;
            }
        }

        return NULL;
    }

    ///////////////////////////////////////////////////////////////////////////////
    public static function doesUrlMatchRoute(string $url, string $route): bool {
        // if there are no special parameters (:param)
        if ($url === $route) {
            return true;
        }

        $urlParts   = self::splitUrl($url);
        $routeParts = self::splitUrl($route);

        if (count($urlParts) != count($routeParts)) {
            return false;
        }

        // cycle through all parts of the url
        // and check if they correspond with the same parts of the route
        foreach ($urlParts as $pos => $value) {
            $isPartParameter = preg_match('/^:.+/', $routeParts[$pos]);

            // if the part is not a special parameter (:param),
            // but the url and route parts don't match,
            // then URL does not match route
            if ( ! $isPartParameter && $value !== $routeParts[$pos]) {
                return false;
            }

            // if the part is a special parameter,
            // add its value to the request params array
            elseif ($isPartParameter) {
                self::addRequestParam($routeParts[$pos], $value);
            }
        }

        return true;
    }


    ///////////////////////////////////////////////////////////////////////////////
    // remove any leading and trailing slashes, separate by / and filter out empty parts
    private function splitUrl(string $url): array {
        $trimmedUrl = trim($url, '/');
        $parts      = explode('/', $trimmedUrl);

        return array_filter($parts);
    }

    ///////////////////////////////////////////////////////////////////////////////
    private static function addRequestParam(string $param, string $value): void {
        // remove double dots
        $param = preg_replace('/^(:)/', '', $param);

        self::$requestParams[$param] = $value;
    }

    ///////////////////////////////////////////////////////////////////////////////
    public static function getRequestParams(): array {
        return self::$requestParams;
    }

    ///////////////////////////////////////////////////////////////////////////////
    public static function getRequestParam(string $param): ?string {
        $requestParams = self::$requestParams;
        return $requestParams[$param] ?? $_GET[$param] ?? NULL;
    }

    ///////////////////////////////////////////////////////////////////////////////
    public static function redirect($target, int $code = 301): void {
        // if the passed argument is a params array,
        // use the router function to get its address
        $url = is_array($target) ? self::url($target) : (string) $target;

        header('Location: ' . $url, true, $code);
        exit;
    }
}
