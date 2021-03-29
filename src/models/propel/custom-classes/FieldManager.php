<?php

abstract class FieldManager {

    const ASSEMBLY_FIELD      = 'assembly_type_id',
          CENSUS_FIELD        = 'population_census_id',
          SUFFRAGE_FIELD      = 'active_suffrage',
          THRESHOLD_FIELD     = 'threshold_percentage',
          VALID_VOTES_FIELD   = 'total_valid_votes',
          INVALID_VOTES_FIELD = 'total_invalid_votes',
          PASSED_PARTIES      = 'passed_parties',

          PARTIES_FIELD       = 'parties',
          PARTY_TITLE         = 'party_title',
          PARTY_ABBREVIATION  = 'party_abbreviation',
          PARTY_ID            = 'party_id',
          PARTY_TOTAL_VOTES   = 'total_votes',
          PARTY_ORD           = 'ord',
          PARTY_COLOR         = 'party_color',
          VOTES_PERCENTAGE    = 'votes_percentage',

          CANDIDATES_FIELD    = 'independent_candidates',
          CAND_NAME_FIELD     = 'name',
          CAND_VOTES_FIELD    = 'votes',
          CAND_CONST_FIELD    = 'constituency_id',

          VOTES_FIELD         = 'parties_votes',
          VOTES_PARTY_FIELD   = 'election_party_id',
          VOTES_CONST_FIELD   = 'constituency_id',
          VOTES_VOTES_FIELD   = 'votes',

          GLOBAL_CONSTITUENCY_MESSAGE = 'constituencies_fields';
}