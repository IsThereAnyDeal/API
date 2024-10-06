## 2.6.0

### Deprecations
- `/games/prices/v2` endpoint has been deprecated, please update to new v3 endpoint

### Changes

- `deals/v2` endpoint now includes 3 month and 1 year historical low
- `games/overview/v2` now properly respects `shops` parameter for historical lows

### New

- added `/games/prices/v3` endpoint
  - `nondeals` parameter from v2 has been replaced with `deals` parameter - the behavior is switched.
    By default you will get all prices, with `deals` parameter you will only get deals.
    This has been changed because it was often confusing why there are no prices in the response. 
  - `historyLow` has moved up one level, from individual deals to game, and now also lists 3 month low and 1 year low

- added notification endpoints
  - `notifications/v1` - list all current user's notifications
  - `notifications/waitlist/v1` - get detail of Waitlist notification (games and passing deals) 
  - `notifications/read/v1` - mark single notifaction as read
  - `notifications/read/all/v1` - mark all notifications are read

- added rudimentary support for Webhooks. Currently we support Waitlist notification event and ping event. 
 

## 2.5.0

### Breaking changes:
- Undocummented legacy APIs have been removed
- Unstable endpoints have been promoted to stable, see more in Lookup section below

### What's new:
- improvements to docs structure

#### Collection
- added `List Copies`, `Add Copies`, `Update Copies`, `Delete Copies` endpoints
- added `Get all Categories`, `Create new Category`, `Update Categories`, `Delete Categories` endpoints 
- `Game in Collection` endpoint now also returns games' asset map and category ID

#### Lookup
- added `Lookup ITAD game IDs by title` endpoint 
- unstable lookup endpoints have been made stable: `Lookup ITAD game IDs by IDs on shop` and `Lookup game IDs on shop by ITAD game IDs`.  
  The unstable URLs are henceforth deprecated, but for the time being they will redirect to new endpoints.  
  The redirect will be removed in future update.

#### Notes
- added `Get notes`, `Add or edit notes`, `Delete notes` endpoints.  
  These endpoints require new `notes_read` or `notes_write` OAuth scopes 

#### Profiles and Sync
It is now possible to link your app with IsThereAnyDeal. This will enable you to use sync endpoints,
which provide easier way to track user's games, for both Waitlist and Collection.

Games tracked via API Sync will behave in the same way as games tracked by ITAD with natively supported remote profiles.

Instead of ITAD periodically pulling games from remote profile, you will have to push these to ITAD when appropriate.

- added `Link profile`, `Unlink profile` endpoints
- added `Sync Waitlist`, `Sync Collection` endpoints

#### Fixes
- fixed `History log` endpoint not correctly filtering stores, when `since` parameter is used
- fixed behavior of `History log` endpoint around `since` timestamp cut-off point
- fixed empty asset map being returned as empty array instead of empty object (#6)
