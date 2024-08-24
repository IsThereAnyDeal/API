<?php
namespace IsThereAnyDeal\SDK\OAuth;

enum EScope: string
{
    case UserInfo = "user_info";
    case UserGames_Read = "notes_read";
    case UserGames_Write = "notes_write";
    case Profiles = "profiles";
    case WaitlistRead = "wait_read";
    case WaitlistWrite = "wait_write";
    case CollectionRead = "coll_read";
    case CollectionWrite = "coll_write";
}
