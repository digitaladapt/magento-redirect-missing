# magento-redirect-missing
Magento 2 Module to aid development

## Purpose
Development/Testing servers frequently are missing asset files.

My solution is a simple module which intercepts 404 responses
and replaces them with redirects to production.

A config file in the root of your Magento website identifies where
to redirect responses to.

Example of `redirect-missing.json`:
```json
{
  "enabled": true,
  "redirect": "https://<PRODUCTION-DOMAIN>"
}
```
