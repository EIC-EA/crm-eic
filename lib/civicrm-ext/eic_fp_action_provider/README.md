# eic_fp_action_provider
Provides reusable CiviCRM Form-Processor action-provider conditions for the EIC
extensions. It currently ships the `CompareParameters` condition, which compares
two run-time parameters against each other with a configurable operator.

This extension is deliberately separate from the form-processor consumers (such
as `eic_eu_survey_form_processor`) so that the condition is registered by an
extension that is enabled *before* the container is rebuilt during install. That
ordering is what lets a consumer import form processors that reference the
condition during its own enable, without hitting a stale-container `null`.

This is an [extension for CiviCRM](https://docs.civicrm.org/sysadmin/en/latest/customize/extensions/), licensed under [AGPL-3.0](LICENSE.txt).

## Getting Started

(* FIXME: Where would a new user navigate to get started? What changes would they see? *)

## Known Issues

(* FIXME *)
