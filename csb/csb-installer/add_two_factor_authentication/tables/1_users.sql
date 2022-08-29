alter table users
    add `two_factor_enabled` tinyint(1) NOT NULL DEFAULT '0',
    add `two_factor_secret` varchar(64) DEFAULT NULL
;

delimiter //
create trigger disable_TFA
    before update on users
    for each row
    if new.two_factor_enabled = 0
    then
        set new.two_factor_secret=NULL;
    end if; //
delimiter ;
