<?php

/**
 * @brief  Xoops グローバルサーチのための検索
 * @param mixed $queryarray
 * @param mixed $andor
 * @param mixed $limit
 * @param mixed $offset
 * @param mixed $userid
 * @return array
 * @return array
 * @author minahito
 * @varsion $Id: search.inc.php,v 1.1 2006/02/22 15:34:54 mikhail Exp $
 */
function bxbbs_search($queryarray, $andor, $limit, $offset, $userid)
{
    global $xoopsDB;

    $sql = 'SELECT id,bbs_id,uid,title,message,inputdate from ' . $xoopsDB->prefix('bxbbs_message');

    $whereflag = false;

    if (0 != $userid) {
        $sql .= ' where uid=' . $userid . ' ';

        $whereflag = true;
    }

    if (is_array($queryarray) && $count = count($queryarray)) {
        if ($whereflag) {
            $sql .= ' AND ';
        } else {
            $sql .= ' WHERE ';
        }

        $sql .= " ((title LIKE '%$queryarray[0]%' OR message LIKE '%$queryarray[0]%')";

        for ($i = 1; $i < $count; $i++) {
            $sql .= " $andor ";

            $sql .= "(title LIKE '%$queryarray[$i]%' OR message LIKE '%$queryarray[$i]%')";
        }

        $sql .= ') ';
    }

    $sql .= ' ORDER BY inputdate DESC';

    $result = $xoopsDB->query($sql, $limit, $offset);

    $ret = [];

    $i = 0;

    while (false !== ($myrow = $xoopsDB->fetchArray($result))) {
        $ret[$i]['link'] = 'message.php?id=' . $myrow['id'];

        $ret[$i]['title'] = $myrow['title'];

        $ret[$i]['time'] = $myrow['inputdate'];

        $ret[$i]['uid'] = $myrow['uid'];

        $i++;
    }

    return $ret;
}
