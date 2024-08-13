<?php
namespace IsThereAnyDeal\SDK\OAuth;

enum EScope: string
{
    case UserInfo = "user_info";
    case WaitlistRead = "wait_read";
    case WaitlistWrite = "wait_write";
    case CollectionRead = "coll_read";
    case CollectionWrite = "coll_write";
}
