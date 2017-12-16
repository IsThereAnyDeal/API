FORMAT: 1A
HOST: https://api.isthereanydeal.com

# IsThereAnyDeal.com API

This API provides (or will provide) programatic access to features found on IsThereAnyDeal.com such as info about current
deals and historical prices as well as provides access to users' data and manipulation with them.

Whole API is under active development, so if you miss some endpoints they may be coming in the future. You can use our [issue tracker](https://bitbucket.org/tfedor/isthereanydeal.com/issues)
to provide feedback or request new features, or you can use some of the contacts listed on [the web](https://isthereanydeal.com/#/page:web/contact).

> In this API we will be refering to all apps, browser extensions or any other software as an *app*.

# Latest changes

**2016-03-20** Several new endpoints: game/plain/id, game/prices, game/lowest, game/bundles, game/info, deals/list, added [mailing list](https://isthereanydeal.com/devmail/)  

# Do's and don'ts

Whole API is provided in good faith. We trust you won't misuse the API to damage IsThereAnyDeal.com, its users or its reputation.

* You **may** use this API for commercial purposes **IF** the resulting app is available to public. If you want to use this API privately please contact us.
* You **should** provide a link to IsThereAnyDeal.com or mention IsThereAnyDeal.com API.
* You **must not** make an impression that you are affiliated with IsThereAnyDeal.com, unless agreed otherwise. If you are interested in making an official app, please contact us.
* You **must not** change provided data in any way. This means that you can't remove affiliate tags from the URLs, change prices and so on. You **may** use only part of data provided and enrich them from different sources (e.g. you may add images to games).  
* You **must not** make an app that could be considered a competition to IsThereAnyDeal.com.
* You **must not** use this API to directly or indirectly help the competition of IsThereAnyDeal.com.

All data are provided *as-is*. We reserve the right to deny you access to the API at any point without notice. If you are not sure about any of the points, please contact us.

# Following changes

You can join developer's [mailing list](https://isthereanydeal.com/devmail/), which will be used to occassionally notify you about latest changes in the API.

# Endpoints

All regular API endpoints will list current version, type and scope, if endpoint requires it.

## Version

Almost all API endpoints will be versioned. Version is denoted by letter `v` and number, like `v01` for version 1. Zeroes are optional and it doesn't matter whether you use `v1` or `v0001`. We prefer two digit format.

In general, version number will change only when the format of the response changes. We may add more info to an endpoint and not change the version number.

You should always try to use the latest version if possible. Older versions may become
*deprecated* (we will still make updates to ensure that endpoint works, but you should switch to a newer version)
or *unsupported* (we no longer guarantee the endpoint will work). This will be denoted by `.deprecated`
or `.unsupported` field in the response.

## Type

There are three levels of API endpoints that we provide:

* public
* protected
* private

You can access *public* endpoints without any other prerequisites.
These endpoints will provide some basic info about ITAD, such as covered stores or regions.
 
To access *protected* endpoints you will need *API key* or *OAuth token*, depending on whether the API manipulates or reads user's data or not.
 
To access *private* endpoints you will need explicit permission. You can request it via email listed on the [contact page](https://isthereanydeal.com/#/page:web/contact).
Misuse of private endpoints will lead to restricting all API access.

An endpoint may be of multiple types, e.g. `public/protected`. That means that you can get some basic info from
the endpoint but to get more detailed information you will need to use your API key.

## Scope

Endpoints that require user data manipulation will require you to define scope. Scope is used to tell user what your app
may do. You should define minimal scope your app really needs.
 
## Debugging

For all endpoints that return json you may use `?pretty` parameter to get prettified output.

Some endpoints may contain `.meta` field describing the output, such as if the request was loaded from cache or not.
 
## Regions

Some endpoints may require you to specify region. Often in such endpoints the accuracy of the results may be
improved by also supplying country code. Internally ITAD uses hidden regions, which is a fancy name for exceptions for
some region/store/country combinations.

> At the time of writing this an example may be Green Man Gaming and Sweden. Although Sweden is listed in `eu1` region
on ITAD, GMG uses GBP as a currency for Sweden, instead of EUR.
  
If you don't specify the country, *some* country from given region will be used, most probably the first one listed.

## Optional data

Some endpoints may offer additional data which are not included in default response. For example,
[Stores endpoint](#reference/web/stores) by default returns only `id`, `title` and `color` for each store but with
`optional` parameter you can request data about `deals` and `catalog` of the store.

When endpoint offers optional data, all possible values will be listed in specification. Moreover, you can list
more options if you separate them by comma.
 
# Your apps

If you want to access protected or private endpoints, you will have to [register](https://isthereanydeal.com/apps/new/) your app.

> Please note that you can't change app's name after you register it. All registered apps will be tied to your account.
> Consider creating new account if you want to manage app in team or don't want it to be associated with your ITAD's account

## Managing your apps

You can manage all apps you create from [Mine apps](https://isthereanydeal.com/apps/mine/) section.

### OAuth

Upon registration, `client ID` and `client secret` will be generated for you. To be able to use them you need to set correct `redirect URI`.
You can change `redirect URI` at any time. You may also list more than one URL, separated by space.

### API keys

API keys are used to access protected and private endpoints, which do not operate with users' data. You can request new keys and make them active or inactive as you please,
but you can only have limited number of keys active at one time.

> For both API keys and OAuth credentials, take into consideration that they uniquely identify your app. If you misuse API access, you may be denied access to some or all endpoints.

### Basic info

Allows you to change app description or URLs. Remember that you have to set at least one valid URL before releasing your app.

### Rights

This section will list any permissions or restrictions that may apply to your app.

## Releasing apps

Before releasing your app to public you have to set at least one URL at which your app will be available. This may be a GitHub repo, your personal page, browser extension repository or any other website.
You will also have to set up your username, if you haven't already.

Once released, your app will be listed in our own [repository](https://isthereanydeal.com/apps/).

# Group OAuth authentication

OAuth authentication is required for protected and private endpoints that manipulate users' data. For our API we use OAuth 2.
A lot has been written about OAuth, but in a short and simplified way it works something like this:

1. Your app will redirect user to an authorization page
2. User allows access for your app
3. Request is redirected back to a `redirect URI` with an *authorization code* which you will use to get token or with *token* itself, depending on the grant type you used.
4. With token acquired you may finally access API endpoints 

## Grant types

Currently we allow following grant types:

* Authorization code
* Implicit
* Refresh token

For more information about grant types take a look at e.g. [A guide to OAuth 2.0 grants by Alex Bilbie](http://alexbilbie.com/2013/02/a-guide-to-oauth-2-grants/).  
            
## Tokens summary

Unlike API keys, OAuth tokens will expire. Following table lists how long the tokens are valid.

| Key                | Use      | Lifetime |          |
|--------------------|:--------:|:--------:|:--------:|
| Authorization code | once     | 60       | 1 minute |
| Access token       | multiple | 2073600  | 24 days  |
| Refresh token      | once     | 2073600  | 24 days  |

## Authorize [/oauth/authorize/]

### Authorize app [GET /oauth/authorize/{?response_type,client_id,state,scope,redirect_uri}]

Provides a way for user to authorize your app.

Redirect user to this URL and let him decide whether he will authorize your app. After user's action, the request will be sent back to `redirect_uri` you set for your app or you sent as a parameter.

Response depends on the grant type you are using. If you are using `implicit` grant type you will receive token in a URL fragment, with `authorization code` grant type you will get one-time use code to ask for token.

**Always** define **minimal** scope your app will really need. If you're not using endpoints to user's Collection data,
there's no point to request Collection scopes. If your app grows in the future you can let user re-authorize your app.

**Always** use the API URL listed, even though the real request will be redirected to different URL. This will ensure that if we move authorization page, your requests will still work.

**See also:** [some reading about `state` parameter](http://www.thread-safe.com/2014/05/the-correct-use-of-state-parameter-in.html)

+ Parameters
    + client_id (required) - OAuth client ID
    + response_type: `code` (enum[string], required)
        + Members
            + `code` - authorization code
            + `token` - token as part of URL fragment (implicit grant type)
    + state (required) - State for CSRF check
    + scope: `wait_read wait_write` (required) - Space separated list of scopes your app wants to access
    + redirect_uri (optional) - URL redirect where the response should be sent after user's action
        Must match `redirect_uri` you set up for your app.
        If you have listed multiple `redirect_uri`'s for your app then this parameter is **required**.
    
+ Response 200
    
    If user authorizes your app response will be sent back to `redirect_uri`.
    When using `authorization_code` grant the request will include a `code` and `state` parameter.
    In case of `implicit` grant, the request will contain token data in an URL fragment.
    
    + Body
    
+ Response 400
    
    Authorization server returns 400 if there's an error in `client ID` or `redirect URI`   
    
    + Body
    
            {
                "error": "invalid_client",
                "error_description": "The client id supplied is invalid"
            }
    
+ Response 302
    
    If there was an error in other parameters, request will be taken back to `redirect URI` with `error`, `error_description` and `state` parameters in URL.
    
    Please note that our implementation is stricter than the standard, since we always require you to specify `scope`
    
    + Body



## Token [/oauth/token/]

There are 2 types of tokens: 

* access tokens
* refresh tokens

You will use **access tokens** to access API endpoints and **refresh tokens** to generate new access token.

### Request access token [POST]

+ Request (application/x-www-form-urlencoded)

    | Attribute     | Required | Value | Description |
    |---------------|:--------:|-------|-------------|
    | grant_type    | &#10003; | `authorization_code` | |
    | code          | &#10003; | | Authorization code generated after authorize request |
    | redirect_uri  |          | | **Required** only if authorization request was issued with `redirect_uri` parameter. In such case values must be identical. |
    | client_id     | &#10003; | | OAuth client ID |
    | client_secret | &#10003; | | OAuth client secret |

    + Body 
    
            grant_type=authorization_code&code={code}&client_id={client_id}&client_secret={client_secret}

+ Response 200
       
    + Body
    
            {
                "access_token": "c629cdcefbe459393ac1337bc2282e91880c0b0c",
                "expires_in": 3600,
                "refresh_token": "ae6ef5b68693368013e0333b499555c987c577a5",
                "scope": "wait_read wait_write",
                "token_type": "Bearer"
            }

### Request refresh token [POST]

+ Request (application/x-www-form-urlencoded)

    | Attribute     | Required | Value | Description |
    |---------------|:--------:|-------|-------------|
    | grant_type    | &#10003; | `refresh_token` | |
    | refresh_token | &#10003; | | Refresh token |
    | client_id     | &#10003; | | OAuth client ID |
    | client_secret | &#10003; | | OAuth client secret |

    + Body 
    
            grant_type=refresh_token&refresh_token={refresh_token}&client_id={client_id}&client_secret={client_secret}

+ Response 200

    + Body
            
            {
                "access_token": "c6f36addc6aec47496683d46f03d7f118511a209",
                "expires_in": 3600,
                "refresh_token": "fcd196bbd4aefd9cdd213e1033fc8c7603a96274",
                "scope": "wait_read wait_write",
                "token_type": "Bearer"
            }

# Group Web

## Regions [/v01/web/regions/]
```Version: v01 | Type: public```

Lists all covered regions with the country codes associated to the region and currency information.

### List covered regions [GET]

+ Response 200

        {
            "data":
                {
                    "eu1":{"countries":["AL","AD","AT","BE","DK","FI","FR","DE","IE","LI","LU","MK","NL","SE","CH"],"currency":{"code":"EUR","html":"&euro;","sign":"\u20ac"}},
                    "eu2":{"countries":["BA","BG","HR","CY","CZ","GR","HU","IT","MT","MC","ME","NO","PL","PT","RO","SM","RS","SK","SI","ES","VA","EE","LV","LT"],"currency":{"code":"EUR","html":"&euro;","sign":"\u20ac"}},
                    "uk":{"countries":["GB"],"currency":{"code":"GBP","html":"&pound;","sign":"\u00a3"}},
                    "us":{"countries":["US","CA"],"currency":{"code":"USD","html":"$","sign":"$"}},
                    "br":{"countries":["BR"],"currency":{"code":"USD","html":"$","sign":"$"}},
                    "au":{"countries":["AU"],"currency":{"code":"USD","html":"$","sign":"$"}}
                }
        }

## Stores in region [/v02/web/stores/{region}/{?country,optional}]
```Version: v02 | Type: public```

Lists all covered stores for given *region*. Optionally also loads current *deals* and *catalog* game count
and unix timestamp of last update.

> Note that IDs of stores don't change even if the store is re-branded. This means that you can hardcode ID of the store
  in your app if you need to use them.

### List covered stores for given region [GET]

+ Parameters
    
    + region: `us` (required) - Region name as used by ITAD
    + country: `US` (optional) - Country code to improve accuracy
    + optional (enum[string], optional) - Separate multiple values with comma
        + Members
            + `deals`
            + `catalog`

+ Response 200

    + Body

            {
                ".meta":{"region":"us","country":"US"},
                "data":
                    [
                        {"id":"steam","title":"Steam","color":"#9ffc3a"},
                        {"id":"gamersgate","title":"GamersGate","color":"#fc5d5d"},
                        {"id":"gamesplanet","title":"GamesPlanet UK","color":"#f6a740"},
                        {"id":"greenmangaming","title":"GreenMan Gaming","color":"#21a930"},
                        {"id":"gog","title":"GOG","color":"#f16421"},
                        {"id":"dotemu","title":"DotEmu","color":"#f6931c"},
                        {"id":"amazonus","title":"Amazon","color":"#fcc588"},
                        {"id":"nuuvem","title":"Nuuvem","color":"#b5e0f4"}
                    ]
                }

## Covered Stores [/v01/web/stores/all/]
```Version: v01 | Type: public```

Similar to previous endpoint, this one lists stores covered by ITAD. Difference is that this endpoint does not consider
region and always returns all stores covered. Since this is global for whole ITAD, you can't list last update timestamp
or counts of games on the store. 

> Note that IDs of stores don't change even if the store is re-branded. This means that you can hardcode ID of the store
  in your app if you need to use them.

### List all covered stores [GET]

+ Response 200

    + Body

            {
                "data":
                    [
                        {"id":"steam","title":"Steam","color":"#9ffc3a"},
                        {"id":"gamersgate","title":"GamersGate","color":"#fc5d5d"},
                        {"id":"gamesplanet","title":"GamesPlanet UK","color":"#f6a740"},
                        {"id":"greenmangaming","title":"GreenMan Gaming","color":"#21a930"},
                        {"id":"gog","title":"GOG","color":"#f16421"},
                        {"id":"dotemu","title":"DotEmu","color":"#f6931c"},
                        {"id":"amazonus","title":"Amazon","color":"#fcc588"},
                        {"id":"nuuvem","title":"Nuuvem","color":"#b5e0f4"}
                    ]
            }

# Group Game

## Identifier [/v02/game/plain/{?key,shop,game_id,url,title,optional}]
```Version: v02 | Type: protected```

ITAD identifies games by *plain* (string). This endpoint will try to fetch a *plain* for you by store game ID, URL
or title. The more information you provide the better the chance to successfully fetch plain.

Identification is done in following order:
 
1. by ID
2. by URL
3. by title
 
If any of the methods succeeds, the process will stop.

If the `plain` identifier was found, it's checked whether it wasn't merged with another game updated.

In the `.meta` part of the response you can see `match` (tells you which part of process succeded) and `active`
(true if the game has pricing info).

Optionally this endpoint can also return title associated to found plain.

### Identify by ID

To uniquely identify the game on different stores and map it to our `plain` we use *store/game ID* combination.
Ideally, game ID is the same the given store uses in their system. This method is the fastest and the most accurate.

Most of the time the ID the store uses to identify the game can be guessed from URL or found in page source. 
However, internally, IDs on ITAD may differ from those used by stores. That is mainly due to historic reasons,
where the real ID could not be fetched (mostly because it was unknown to us what the store uses to identify the game).
In such cases we opted to use part of the URL as ID. If you are not sure what IDs we use for given store, you can
contact us for more information. We don't list it for each store since the situation may change.

> Take into consideration that game ID for given store as ITAD recognizes it may change.

Requires `shop` and `game_id` parameters.

### Identify by URL

The most unreliable method. Since URLs often change by adding various parameters
(e.g. region, country, campaign info) it's not the best idea to try to identify the game solely by URL.
 
Identification by URL will remove the host part of the URL and try to compare the rest with the end of the URL
in ITAD's database.

Requires `shop` and `url` parameters.

### Identify by Title

Identification by title tries fixes some "title flaws" that often show up in stores and tries to match them against
known titles. Should provide reasonable results.

Requires `title` parameter.

### Get plain [GET]

+ Parameters
    
    + key (required) - Your API key
    + shop: `steam` (optional) - Required for ID and URL
    + game_id: `app/377160` (optional)
    + url (optional)
    + title (optional)
    + optional (enum[string], optional) - Separate multiple values with comma
        + Members
            + `title`

+ Response 200

        {".meta":{"match":"id","active":true},"data":{"plain":"dishonored"}}
        
+ Response 200

        {".meta":{"match":false,"active":false},"data":[]}
       

## Multiple plains by ID [/v01/game/plain/id/{?key,shop,ids}]
```Version: v01 | Type: protected```

If you need to get more plains at once, you can use this endpoint. It's limitation is that it works only with IDs and one shop at once.

> This endpoint will provide ONLY `plain` for given `id` (or `null if not found), no additional info like title or whether the entry has active deals.

### Get plain [GET]

+ Parameters
    
    + key (required)
    + shop: `steam` (required)
    + ids: `app/377160,app/96100,sub/28187,sub/1245` (required) - List of IDs separated by comma, max 50

+ Response 200

        {
            "data": {
                "app\/377160": "falloutiv",
                "app\/96100": "defygravity",
                "sub\/28187": "elderscrollsvskyrimlegendaryedition",
                "sub\/1245": null
            }
        }
        

## Prices [/v01/game/prices/{region}/{?key,plains,country}]
```Version: v01 | Type: protected ```

Provides way to get all current prices for selected games. Use `region` and `country` to get more accurate results.

> Prices will be sorted from lowest to highest.

### Get current prices [GET]

+ Parameters
    + region: `eu2` (optional)
    + key (required) - Your API key
    + plains: `endorlight,storyofsurvivor` (required)
    + country: `SK` (optional)

+ Response 200
        
        {
            ".meta": {
                "currency": "EUR"
            },
            "data": {
                "endorlight": {
                    "list": [
                        {
                            "price_new": 0.45,
                            "price_old": 2.35,
                            "price_cut": 81,
                            "url": "https:\/\/indiegamestand.com\/store\/2420\/endorlight",
                            "shop": {
                                "id": "indiegamestand",
                                "name": "IndieGameStand"
                            },
                            "drm": [
                                "steam"
                            ]
                        },
                        {
                            "price_new": 2.99,
                            "price_old": 2.99,
                            "price_cut": 0,
                            "url": "http:\/\/store.steampowered.com\/app\/428430\/?snr=1_7_7_204_150_94",
                            "shop": {
                                "id": "steam",
                                "name": "Steam"
                            },
                            "drm": [
                                "steam"
                            ]
                        }
                    ],
                    "urls": {
                        "game": "https:\/\/isthereanydeal.com\/#\/page:game\/info?plain=endorlight"
                    }
                },
                "storyofsurvivor": {
                    "list": [
                        {
                            "price_new": 3.53,
                            "price_old": 5.29,
                            "price_cut": 33,
                            "url": "https:\/\/indiegamestand.com\/store\/2385\/story-of-the-survivor",
                            "shop": {
                                "id": "indiegamestand",
                                "name": "IndieGameStand"
                            },
                            "drm": [
                                "DRM Free",
                                "steam"
                            ]
                        },
                        {
                            "price_new": 4.99,
                            "price_old": 4.99,
                            "price_cut": 0,
                            "url": "http:\/\/store.steampowered.com\/app\/440950\/?snr=1_7_7_230_150_495",
                            "shop": {
                                "id": "steam",
                                "name": "Steam"
                            },
                            "drm": [
                                "steam"
                            ]
                        },
                        {
                            "price_new": 5.99,
                            "price_old": 5.99,
                            "price_cut": 0,
                            "url": "https:\/\/www.bundlestars.com\/en\/game\/story-of-the-survivor",
                            "shop": {
                                "id": "bundlestars",
                                "name": "Bundle Stars"
                            },
                            "drm": [
                                "steam"
                            ]
                        }
                    ],
                    "urls": {
                        "game": "https:\/\/isthereanydeal.com\/#\/page:game\/info?plain=storyofsurvivor"
                    }
                }
            }
        }


## Historical low [/v01/game/lowest/{region}/{?key,plains}]
```Version: v01 | Type: protected ```

Provides information about historical low selected games had.

### Get historical low [GET]

+ Parameters
    + region: `eu2` (optional)
    + key (required) - Your API key
    + plains: `europauniversalisiv,falloutiv` (required) - List of plains separated by comma
    
+ Response 200
        
        {
            ".meta": {
                "currency": "EUR"
            },
            "data": {
                "europauniversalisiv": {
                    "shop": {
                        "id": "nuuvem",
                        "name": "Nuuvem"
                    },
                    "price": 4.26,
                    "cut": 80,
                    "added": 1419894191,
                    "urls": {
                        "game": "https:\/\/isthereanydeal.com\/#\/page:game\/info?plain=europauniversalisiv",
                        "history": "https:\/\/isthereanydeal.com\/#\/page:game\/price?plain=europauniversalisiv"
                    }
                },
                "falloutiv": {
                    "shop": {
                        "id": "funstock",
                        "name": "FunStock Digital"
                    },
                    "price": 27.24,
                    "cut": 50,
                    "added": 1450883648,
                    "urls": {
                        "game": "https:\/\/isthereanydeal.com\/#\/page:game\/info?plain=falloutiv",
                        "history": "https:\/\/isthereanydeal.com\/#\/page:game\/price?plain=falloutiv"
                    }
                }
            }
        }


## Bundles [/v01/game/bundles/{region}/{?key,plains,limit,expired,sort}]
```Version: v01 | Type: protected ```

Provides information about how many times the game has been bundled and lists these bundles.

> If expiry of the bundle is unknown, `null` will be returned instead of timestamp

### Get info about where the game was bundled [GET]

+ Parameters
    + region: `us` (optional)
    + key (required) - Your API key
    + plains: `endorlight,storyofsurvivor` (required) - List of plains separated by comma
    + limit: 1 (optional) - How many bundles to list at most, `-1` for no limit
    + expired: 0 (optional) - Whether to list expired bundles
    + sort: `recent` (optional) - Sorting order of listed bundles

        `recent` - newest first
        `expiry` - ending soonest first
    
        + Values
            `expiry`
            `recent`

+ Response 200
        
        {
            "data": {
                "endorlight": {
                    "total": 4,
                    "list": [
                        {
                            "title": "Slash & Shoot Bundle",
                            "bundle": "One More Bundle",
                            "start": 1457895360,
                            "expiry": 1458658740,
                            "platforms": [
                                "windows"
                            ],
                            "url": "https:\/\/isthereanydeal.com/specials\/3172\/"
                        }
                    ],
                    "urls": {
                        "game": "https:\/\/isthereanydeal.com\/#\/page:game\/info?plain=endorlight",
                        "bundles": "https:\/\/isthereanydeal.com\/specials\/#\/search:endorlight;\/filter:bundle"
                    }
                },
                "storyofsurvivor": {
                    "total": 3,
                    "list": [
                        {
                            "title": "Every Monday Bundle 102",
                            "bundle": "Indie Gala",
                            "start": 1457974140,
                            "expiry": null,
                            "platforms": [
                                "windows"
                            ],
                            "url": "https:\/\/isthereanydeal.com/specials\/3176\/"
                        }
                    ],
                    "urls": {
                        "game": "https:\/\/isthereanydeal.com\#\/page:game\/info?plain=storyofsurvivor",
                        "bundles": "https:\/\/isthereanydeal.com\specials\/#\/search:storyofsurvivor;\/filter:bundle"
                    }
                }
            }
        }


## Info [/v01/game/info/{?key,plains}]
```Version: v01 | Type: protected ```

Provides basic information about game.

> Greenlight status and reviews will be null if unknown and array otherwise, other fields are boolean. Check example.

With `optional=metacritic` parameter you can also get summary for this game from Metacritic, if we found one. However,
you **have to** mention that this text is authored by Metacritic and you should link to Metacritic page - you can find
url in `urls` field. If Metacritic info is not available, it will be `null`.

### Get info about game [GET]

+ Parameters
    + key (required) - Your API key
    + plains: `europauniversalisiv,linelight` (required) - List of plains separated by comma
    + optional (enum[string], optional) - Separate multiple values with comma
        + Members
            + `metacritic`

+ Response 200
        
        {
            "data": {
                "europauniversalisiv": {
                    "greenlight": null,
                    "is_package": false,
                    "is_dlc": false,
                    "achievements": true,
                    "trading_cards": true,
                    "early_access": false,
                    "reviews": {
                        "steam": {
                            "perc_positive": 92,
                            "total": 15712,
                            "text": "Very Positive"
                        }
                    },
                    "urls": {
                        "game": "https:\/\/isthereanydeal.com\/#\/page:game\/info?plain=europauniversalisiv",
                        "package": "https:\/\/isthereanydeal.com\/#\/page:game\/package?plain=europauniversalisiv",
                        "dlc": "https:\/\/isthereanydeal.com\/#\/page:game\/dlc?plain=europauniversalisiv"
                    }
                },
                "linelight": {
                    "greenlight": {
                        "status": "greenlit",
                        "url": "http:\/\/steamcommunity.com\/sharedfiles\/filedetails\/?id=637687580"
                    },
                    "is_package": false,
                    "is_dlc": false,
                    "achievements": false,
                    "trading_cards": false,
                    "early_access": false,
                    "reviews": null,
                    "urls": {
                        "game": "https:\/\/isthereanydeal.com\/#\/page:game\/info?plain=linelight",
                        "package": "https:\/\/isthereanydeal.com\/#\/page:game\/package?plain=linelight",
                        "dlc": "https:\/\/isthereanydeal.com\/#\/page:game\/dlc?plain=linelight"
                    }
                }
            }
        }


# Group Deals

## Recent Deals [/v01/deals/list/{region}/{?key,country,offset,limit}]
```Version: v01 | Type: protected ```

Provides list of deals sorted from newest to oldest.

> Use region and country parameters to get more accurate listing

### Get deals [GET]

+ Parameters
    + region: `eu2` (optional)
    + key (required) - Your API key
    + country: `CZ` (optional)
    + offset: 0 (optional)
    + limit: 20 (optional) - How many deals to get in one query, maximum 50

+ Response 200
        
        {
            ".meta": {
                "currency": "EUR"
            },
            "data": {
                "count": 7984,
                "list": [
                    {
                        "plain": "theatreofwariiafricaiixiviii",
                        "title": "Theatre of War 2 Africa 1943",
                        "price_new": 7.98,
                        "price_old": 17.74,
                        "price_cut": 55,
                        "added": 1458442019,
                        "shop": {
                            "id": "amazonus",
                            "name": "Amazon"
                        },
                        "drm": [
                            ""
                        ],
                        "urls": {
                            "buy": "http:\/\/www.amazon.com\/Theatre-War-Africa-1943-Download\/dp\/B004QO9UUM\/ref=sr_1_4163_twi_sof_1\/185-4887647-7992563?s=videogames-download&ie=UTF8&qid=1458442018&sr=1-4163&refinements=p_n_availability%3A1238047011%2Cp_n_operating_system_browse-bin%3A2346238011%2Cp_n_condition-type%3A2224366011%2Cp_36%3A-700&tag=isthcom0a-20",
                            "game": "https:\/\/isthereanydeal.com\/#\/page:game\/info?plain=theatreofwariiafricaiixiviii"
                        }
                    },
                    {
                        "plain": "mightandmagicheroesviideluxeedition",
                        "title": "Might & Magic Heroes VII Deluxe Edition",
                        "price_new": 56.99,
                        "price_old": 59.99,
                        "price_cut": 5,
                        "added": 1458441819,
                        "shop": {
                            "id": "dlgamer",
                            "name": "DLGamer"
                        },
                        "drm": [
                            "uplay"
                        ],
                        "urls": {
                            "buy": "http:\/\/www.dlgamer.eu\/download-might-magic-heroes-7-deluxe--pc_games-p-29359.html?affil=3429886050",
                            "game": "https:\/\/isthereanydeal.com\/#\/page:game\/info?plain=mightandmagicheroesviideluxeedition"
                        }
                    },
                    
                    ... rest of response omitted for clarity ...
                    
                ],
                "urls": {
                    "deals": "https:\/\/isthereanydeal.com\/"
                }
            }
        }


# Group Specials

## Create [/v01/specials/create/{?key,type}]
```Version: v01 | Type: private (request access)```

Endpoint for creating Specials programatically. May be used by bundle pages to create bundles, shops to add vouchers as soon as they launch etc.
Special will be created under account of the app owner, same rules will apply as if the Special was created via [public interface](https://isthereanydeal.com/specials/interface/bundle/).

Body of the POST request are JSON data, for specific format for each type of Special look at examples. You may omit some
 parts of the request, if they are not neccessary for given Special type. If omitted, default value will be used.

Upon successful creation, the response will contain ID of newly created Special and URL to check it on ITAD.

If the creation fails, response will contain details of errors when possible.

> `bundle` and `media` types are synonyms, the actual type will be decided based on content
 
**Important:**
    
- misuse of this endpoint will lead to removal of provider from ITAD's Specials and restricting further API access
- for covered regions see ITAD or [regions endpoint](#reference/web/regions)
- for covered stores and their IDs see ITAD or [covered stores endpoint](#reference/web/covered-stores/list-all-covered-stores)
- avoid marketing speech, keep entries as short as possible and informative only
- when listing media item type, keep it as short as possible, ideally 5 characters maximum
- all dates and times (expiry, publish, price change) are GMT in `YYYY-mm-dd HH:ii` format
 
### Create Special [POST]

+ Parameters
    + key (required)
    + type: `bundle` (required)
        + Members
            + `bundle`
            + `media`
            + `voucher`
            + `giveaway`
            + `other`
            
+ Request Create bundle

    + Body
    
            {
                "title": "Test Bundle",
                "url": "http:\/\/humblebundle.com",
                "message": "",
                "expiry": null,
                "publish": null,
                "regions": [
                    "eu2",
                    "us",
                    "uk"
                ],
                "page_id": "humblebundle",
                "tiers": [
                    {
                        "price": 1,
                        "price_fixed": 1,
                        "price_timestamp": null,
                        "price_new": null,
                        "note": "",
                        "games": [
                            {
                                "title": "Europa Universalis IV",
                                "note": "",
                                "keys": [
                                    "steam"
                                ],
                                "platforms": [
                                    "windows",
                                    "mac"
                                ]
                            },
                            {
                                "title": "BeatHazard",
                                "note": "",
                                "keys": [
                                    "steam",
                                    "DRM Free"
                                ],
                                "platforms": [
                                    "windows"
                                ]
                            }
                        ],
                        "media": [
                            {
                                "title": "Super awesome merchandise",
                                "note": "available nowhere else",
                                "author": "",
                                "type": "merch"
                            }
                        ]
                    },
                    {
                        "price": 5,
                        "price_fixed": 1,
                        "price_timestamp": "2016-05-05 12:00",
                        "price_new": null,
                        "note": "",
                        "games": [
                            {
                                "title": "Stellaris",
                                "note": "",
                                "keys": [
                                    "steam"
                                ],
                                "platforms": [
                                    "windows",
                                    "mac",
                                    "linux"
                                ]
                            }
                        ],
                        "media": []
                    }
                ]
            }
            
+ Response 200

            {
              "data": {
                "id": 1548,
                "url": "https://isthereanydeal.com/specials/1548/"
              }
            }

            
+ Request Create Voucher

    You have to include voucher code in `title` and either `cut` or `price`. `cut` will create a "xy% off" voucher, `price` will create voucher that sets final price to specific amount.
    
    Listing games is optional but preferable.

    + Body
    
            {
                "title": "VOUCHER-CODE-HERE",
                "url": "http:\/\/greenmangaming.com",
                "message": "",
                "expiry": null,
                "publish": null,
                "regions": [
                    "eu2",
                    "us",
                    "uk"
                ],
                "cut": 25,
                "price": null,
                "minimum": null,
                "shop": "greenmangaming",
                "games": [
                    {
                        "title": "Europa Universalis IV"
                    },
                    {
                        "title": "BeatHazard"
                    }
                ]
            }
 
+ Response 200

            {
              "data": {
                "id": 1549,
                "url": "https://isthereanydeal.com/specials/1548/"
              }
            }
            
+ Request Create Giveaway

    Make title as descriptive as possible. Giveaway has to include at least one game or media item.

    + Body
    
            {
                "title": "Free BeatHazard and merchandise on Example",
                "url": "http:\/\/example.com\/",
                "message": "",
                "expiry": null,
                "publish": null,
                "regions": [
                    "eu2",
                    "us",
                    "uk"
                ],
                "games": [
                    {
                        "title": "BeatHazard",
                        "note": "",
                        "keys": [
                            "steam",
                            "DRM Free"
                        ],
                        "platforms": [
                            "windows"
                        ]
                    }
                ],
                "media": [
                    {
                        "title": "Super awesome merchandise",
                        "note": "available nowhere else",
                        "author": "",
                        "type": "merch"
                    }
                ]
            }

+ Response 200

            {
              "data": {
                "id": 1550,
                "url": "https://isthereanydeal.com/specials/1548/"
              }
            }
 
+ Request Create Other

    Category for Specials, that don't fit other categories. `message` is required, avoid marketing speech.

    + Body
    
            {
                "title": "Some special deal",
                "url": "http:\/\/example.com",
                "message": "Description of the Special.",
                "expiry": null,
                "publish": null,
                "regions": [
                    "eu2",
                    "us",
                    "uk"
                ]
            }
            
+ Response 200

            {
              "data": {
                "id": 1551,
                "url": "https://isthereanydeal.com/specials/1548/"
              }
            }            
 
+ Request Example of bad bundle creation request
 
    This bundle is missing page_id and content.
 
    + Body
    
            {
                "title": "Test Bundle",
                "url": "http:\/\/humblebundle.com",
                "message": "",
                "expiry": null,
                "publish": null
            }
             
+ Response 400
             
            {
              "error": "invalid_request",
              "error_description": {
                "page_id": "Unknown bundle page",
                "url": "Unable to check URL",
                "content": "This bundle has no content"
              }
            }


## Publish [/v01/specials/publish/{?key,id}]
```Version: v01 | Type: private (request access)```

Endpoint for publishing Specials. Only Specials that are pending and you are the author can be published.
 
When successful, response will contain URL to published Special.

**Important:**  misuse of this endpoint will lead to removal of provider from ITAD's Specials and restricting further API access

### Publish Special [GET]

+ Parameters
    + key (required)
    + id: `1555` (required) - ID of the Special
    
+ Response 200

            {
              "data": {
                "url": "https://dev.isthereanydeal.com/specials/1555/"
              }
            }

+ Response 400

            {
              "error": "invalid_request",
              "error_description": "Special is not pending"
            }

# Group Waitlist

## Single Game [/v01/user/wait/{region}/{?access_token,plain}]
```Version: v01 | Type: protected | Scope: wait_read```

By supplying `plain` you can check if the game is in user's Waitlist. Response will be `yes` or `no`.

Since Waitlist on ITAD are regional, you can improve the accuracy of the request by defining *region*.
If no region is specified, the endpoint will check whether the game is Waitlisted in *any* region. 

### Check if user has game in Waitlist [GET]

+ Parameters
    + region (optional)
    + access_token (required) - OAuth access token
    + plain: `dishonored` (required)

+ Response 200
        
        {"data": {"in_waitlist": "no"}}

# Group Collection

## Single Game [/v01/user/coll/{?access_token,plain,optional}]
```Version: v01 | Type: protected | Scope: coll_read```

By supplying `plain` you can check if the game is in user's Collection. Response will be `yes` or `no`.

You can supply `optional=stores` to get info about stores at which the user owns the game.
List of stores will be added to output, each store having `id` and `title`, which are the same as stores ITAD is
covering with addition of `other` and `retail`. 

### Check if user has game in Collection [GET]

+ Parameters
    + access_token (required) - OAuth access token
    + plain: `dishonored` (required)
    + optional: `stores` (enum[string], optional) - Separate multiple values with comma
            + Members
                + `stores`

+ Response 200

        {
            "data": {
                "in_collection": "yes",
                "stores": [
                    {
                        "id": "greenmangaming",
                        "name": "Greenmangaming"
                    },
                    {
                        "id": "steam",
                        "name": "Steam"
                    }
                ]
            }
        }
        
## Full Collection [/v01/user/coll/all/{?access_token,optional}]
```Version: v01 | Type: protected | Scope: coll_read```

Returns full Collection of user. Result is the list of collected games.

> Requesting full Collection, saving it for some time and comparing games to the saved list is often prefered than doing
Collection request for each game individually.

You can supply `optional=stores` to get info about stores at which the user owns the game.
Same as in *Collection / Single Game* endpoint. 

### Get full Collection [GET]

+ Parameters
    + access_token (required) - OAuth access token
    + optional: `stores` (enum[string], optional) - Separate multiple values with comma
            + Members
                + `stores`

+ Response 200

        {
            "data": {
                "falloutnewvegas": {
                    "title": "Fallout: New Vegas"
                },
                "racesun": {
                    "title": "Race the Sun"
                },
                "vessel": {
                    "title": "Vessel"
                }
            }
        }