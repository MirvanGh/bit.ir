drop trigger if exists account_balance_update_trigger on transactions;
drop function if exists account_balance_update_trigger();

CREATE OR REPLACE FUNCTION account_balance_update_trigger()
RETURNS trigger AS
$BODY$
BEGIN
WITH transaction_credit_sum AS (select COALESCE(sum(amount), 0) as credit_sum
                              from transactions
                              where account_id = NEW.account_id
                                and type = 'credit'),
     transaction_debit_sum AS (select COALESCE(sum(amount), 0) as debit_sum
                               from transactions
                               where account_id = NEW.account_id
                                 and type = 'debit')
UPDATE accounts a
SET balance = transaction_credit_sum.credit_sum - transaction_debit_sum.debit_sum
    FROM transaction_credit_sum, transaction_debit_sum where a.id = NEW.account_id;
RETURN new;
END;
$BODY$
LANGUAGE plpgsql VOLATILE
 COST 100;

CREATE TRIGGER account_balance_update_trigger
    AFTER INSERT OR UPDATE
                        ON transactions
                        FOR EACH ROW
                        EXECUTE PROCEDURE account_balance_update_trigger();
