<?php

if (!sql::getConnection()) returnError(500, "Internal Server Error", sql::$lastConnectError);
