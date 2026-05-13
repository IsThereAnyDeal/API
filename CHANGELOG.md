## 2.9.0
### Authorization
- Added support for providing api key via `ITAD-API-Key` header instead of query param `key`
- Fixed OAuth2 authorization when user needs to log in via Steam before authorizing app

### Endpoints
- Multiple changes for `/deals/v2` endpoint:
  - Added filters documentation
  - Filters can now be sent as JSON instead of as an encoded string
  - Parameters can now be sent in a request body instead of in query
  - Added support for POST method for improved compatibility in case you can't make a GET request with body
  - OAuth authorization can be used instead of apikey; with OAuth it's possible to use user filters such as "in Waitlist"
  - Added `tagsUnion` filter - games will pass the filter if they have at least one of the tags, not all
  - Added `inBundle` filter - games will pass the filter if they are included in bundle `<id>`
- Added `/bundles/v1` endpoint
- Added `/service/shops/map/v1` endpoint that will list all shops we have ever covered, whether they are currently active or not
- Added `/webhooks/v1` endpoints to allow adding and removing webhooks
- Added two unstable endpoints for games list and games changes - please read docs, I'm looking for feedback
- Endpoints will now work with URLs with trailing slash

### Docs and App Management
- Implemented support for tiered rate-limiting based on whether you have verified your account or not;
  for a transitional period, the limits are kept the same, but they will be lowered for unverified accounts in the near future 
- Rate Limiting section of docs has been rewritten to be less vague
- App setup page will now show you your current usage limits and average usage over past 3 days
- Fixed last usage of API key not being recorded


## 2.8.1
- Added `note` field to bundle object in `/games/bundles/v2` and `/games/overview/v2` endpoints


## 2.8.0
- Added `/internal/wsgf/v1` endpoint


## 2.7.0
- Added `/games/subs/v1` endpoint for listing all subscriptions the game is current in
- Added assets to multiple endpoints: search, lookup, bundled games in overview and game bundle endpoints.
  
  Affected urls:`/games/search/v1`, `/games/lookup/v1`, `/games/overview/v2`, `/games/bundles/v2`


## 2.6.1.
- Fixed `expiry` field format for deal objects
- Added response information to webhooks


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
