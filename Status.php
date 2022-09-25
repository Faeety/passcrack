<?php

enum Status: int
{
    case AWAITING = 1;
    case IN_PROGRESS = 2;
    case CRACKED = 3;
    case IMPOSSIBLE = 4;
}
