CREATE TABLE  `exchan1`.`lotte` (
`lno` INT( 11 ) NOT NULL ,
 `n1` INT( 11 ) NOT NULL ,
 `n2` INT( 11 ) NOT NULL ,
 `n3` INT( 11 ) NOT NULL ,
 `n4` INT( 11 ) NOT NULL ,
 `n5` INT( 11 ) NOT NULL ,
 `n6` INT( 11 ) NOT NULL ,
 `nb` INT( 11 ) NOT NULL
) ENGINE = MYISAM DEFAULT CHARSET = euckr;

INSERT INTO  `exchan1`.`lotte`
SELECT *
FROM  `exchan1`.`lotto` ;