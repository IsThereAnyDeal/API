# API 2, migration guide

With new version of ITAD, there's also going to be a new version of API, because the underlying model has changed.

I will have a legacy layer working for at least three months, starting with thew new ITAD launch, but you should
try to update your apps as soon as possible, since things might break.

I have tried to keep the legacy layer as compatible as possible, but there are some inevitable changes.
Further details below.

New API is not 1:1, I have tried to simplify it, some endpoints were removed. If you miss something,
or you want something that was not yet covered, do let me know.

# New API

Some significant changes in new API:
- Entire documentation is now covered by OpenAPI spec
- URL format for endpoints has changed, endpoints will now list version at the end
- OAuth tokens are now 80 characters long
- OAuth Authorization code flow now requires PKCE (see docs for details)
- OAuth implicit flow is still supported, but it's undocumented, and it's use is not recommended. I will probably drop it at some point
- Responses will no longer show .meta, .deprecated and data properties, data are now returned directly
- When no country is defined in request, fall back will be US

# Legacy API, changes and migration guide

All legacy endpoints are deprecated, please switch to new API as soon as possible.

Plain is no longer used as an identifier of games, instead in API 2 we use UUIDs. Legacy endpoints will still work with plains,
but these map of plain:uuid is a snapshot. No new plains will be computed. This means that either you won't be able to find some games,
or endpoints will return empty string or null when a new game is encountered.

Endpoints secured with OAuth require migration to new OAuth workflow, old tokens will no longer work 

## Web

### `/v01/web/regions/`
- no longer return list of countries that are part of a region
- no alternative provided in API 2

### `/v01/web/stores/all/`
- added `new_id` field to help with migration to new numeric store IDs
- returned color is #000000 for each store
- migrate to `/service/shops/v1`

### `/v02/web/stores/`
- deprecated, returned id is a numeric string, color is #000000
- added new_id field to help with migration to new numeric ids 
- migrate to `/service/shops/v1`

## Deals

### `/v01/deals/list/`
- plain may return empty string for new games, a snapshot of plain map is used to return plains 
- returned list will now behave like a deals list on a website - you will not get duplicate games with different deals in the list anymore
- migrate to `/deals/v2`

## Games

### `/v02/game/plain/`
- title is always included in the response
- url mode will no longer work
- migrate to `/games/lookup/v1`

### `/v01/game/plain/id/`
- will return null for new games that don't have plain computed
- migrate to `/games/lookup/v1`

### `/v01/game/plain/id/`
- no replacement

### `/v01/game/map/`
- no replacement

### `/v01/game/prices/`
- drm is always empty
- migrate to `/game/prices/v2`

### `v01/game/lowest/`
- migrate to `/games/historylow/v1`

### `v01/game/storelow/`
- migrate to `/games/storelow/v2`

### `/v01/game/bundles/`
- platforms will be always empty
- migrate to `/games/bundles/v2`

### `v01/game/info/`
- migrate to `/games/info/v2`

### `/v01/game/overview/`
- `local` value of `optional` has no effect
- urls point to home page or bundles page
- .meta region will show country code instead of legacy region
- migrate to `/games/overview/v2`

## Search

### `/v2/search/search/`
- strict parameter has no effect
- id is now string uuid instead of number
- migrate to `/games/search/v1`

## Waitlist

### `/v01/user/wait/`
- new games will be reported as not in Waitlist
- no direct replacement, you should use `/waitlist/games/v1` to fetch user's Waitlist and check against it

### `/v01/user/wait/all/`
- new games will be missing
- migrate to `/waitlist/games/v1`

### `/v02/user/wait/remove/`
- 'plains' in response will always be empty even when deletion was successful
- migrate to `/waitlist/games/v1`

### `/v01/waitlist/import/`
- **dropped** entirely, won't work in a legacy layer
- no direct replacement, use `/waitlist/games/v1` to add games to Waitlist

### Import via Form
- **dropped** entirely, no alternative in new ITAD

## Collection

### `/v01/user/coll/`
- new games will be reported as not in Collection
- no direct replacement, you should use `/collection/games/v1` to fetch user's Collection and check against it

### `/v01/user/coll/all/`
- **dropped**, it's been deprecated for quite some time

### `/v02/user/coll/all/`
- new games will be missing
- migrate to `/collection/games/v1`

### `/v01/collection/import/`
- **dropped** entirely, won't work in a legacy layer
- no direct replacement, use `/collection/games/v1` to add games to Collection

### Import via Form
- **dropped** entirely, no alternative in new ITAD

## Custom Profiles

### `/v01/profile/link/`
- **dropped**, I don't think anyone used this and there's no alternative in new ITAD

## Stats

### `/v01/stats/waitlist/price/`
- region parameter won't restrict stats to a given region anymore, all users are considered.
  However, region may alter stats - users that use historical low or store low setting will be treated as if they were all in the requested region
- scale has no effect, percentiles are computed for each bucket
- migrate to `/stats/waitlist/v1`

### `/v01/stats/waitlist/cut/`
- region parameter won't restrict stats to a given region anymore, all users are considered
- migrate to `/stats/waitlist/v1`

### `/v01/stats/waitlist/chart/`
- plain may be null
- migrate to `/stats/most-waitlisted/v1`

### `/v01/stats/collection/chart/`
- plain may be null
- migrate to `/stats/most-collected/v1`

### `/v01/stats/popularity/chart/`
- plain may be null
- migrate to `/stats/most-popular/v1`

