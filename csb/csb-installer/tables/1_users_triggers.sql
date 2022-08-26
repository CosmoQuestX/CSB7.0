delimiter //
create trigger disable_TFA
    before update on users
    for each row
    if new.two_factor_enabled = 0
    then
        set new.two_factor_secret=NULL;
    end if; //
delimiter ;
