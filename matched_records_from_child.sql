/*
Sql to get matched records from sub table
*/

SELECT
     parent.id
    ,parent.surname
    ,parent.firstname
    ,parent.tel
    ,parent.mailaddress
    ,sub.status
FROM
    table_a parent
LEFT JOIN
    (
     SELECT
        b.sub_id
       ,b.status
     FROM
     (
      SELECT
         sub_id
        ,status
      FROM
        table_b 
      WHERE
        del_flag = '0'
      AND
        status IN ( 2, 4 )
      ORDER BY 
          CASE WHEN  f_mail_status IS NULL THEN 5 END, FIELD(status, 2,1,3,4) ASC
     ) AS b
     GROUP BY b.sub_id
    ) sub
      ON
    parent.id = sub.sub_id
WHERE
    parent.f_del_flg = '0'
