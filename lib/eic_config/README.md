# eic_config
(*FIXME: In one or two paragraphs, describe what the extension does and why one would download it. *)

This is an [extension for CiviCRM](https://docs.civicrm.org/sysadmin/en/latest/customize/extensions/), licensed under [AGPL-3.0](LICENSE.txt).

## Getting Started

(* FIXME: Where would a new user navigate to get started? What changes would they see? *)

## Known Issues

(* FIXME *)

## How this module was generated

    cd /opt/drupal/vendor/civicrm/civicrm-core/ext/
    civix generate:module eic_config 
    cd eic_config 
    civix export ContactType 4 
    civix export ContactType 5 
    civix export CaseType 3 
    civix export RelationshipType 15 
    civix export RelationshipType 16 
    civix export RelationshipType 17 
    civix export CustomField 1 
