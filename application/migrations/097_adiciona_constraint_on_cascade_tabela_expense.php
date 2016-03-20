<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Migration_adiciona_constraint_on_cascade_tabela_expense extends CI_Migration {

	public function up() {

		$drop_fk_payment = "ALTER TABLE payment DROP FOREIGN KEY EXPENSEID_PAYMENT_FK";
		$this->db->query($drop_fk_payment);

		$fk_payment = "ALTER TABLE payment ADD CONSTRAINT EXPENSEID_PAYMENT_FK FOREIGN KEY (id_expense) REFERENCES expense(id) ON DELETE CASCADE ON UPDATE RESTRICT";
		$this->db->query($fk_payment);

		$drop_fk_expense = "ALTER TABLE expense DROP FOREIGN KEY IDBUDGETPLAN_EXPENSE_FK";
		$this->db->query($drop_fk_expense);
		
		$fk_expense = "ALTER TABLE expense ADD CONSTRAINT IDBUDGETPLAN_EXPENSE_FK FOREIGN KEY (budgetplan_id) REFERENCES budgetplan(id) ON DELETE CASCADE ON UPDATE RESTRICT";
		$this->db->query($fk_expense);  
		 

	}

	public function down() {

		$drop_fk_payment = "ALTER TABLE payment DROP FOREIGN KEY EXPENSEID_PAYMENT_FK";
		$this->db->query($drop_fk_payment);

		$fk_payment = "ALTER TABLE payment ADD CONSTRAINT EXPENSEID_PAYMENT_FK FOREIGN KEY (id_expense) REFERENCES expense(id) ON DELETE RESTRICT ON UPDATE RESTRICT";
		$this->db->query($fk_payment);

		$drop_fk_expense = "ALTER TABLE expense DROP FOREIGN KEY IDBUDGETPLAN_EXPENSE_FK";
		$this->db->query($drop_fk_expense);

		$fk_expense = "ALTER TABLE expense ADD CONSTRAINT IDBUDGETPLAN_EXPENSE_FK FOREIGN KEY (budgetplan_id) REFERENCES budgetplan(id) ON DELETE RESTRICT ON UPDATE RESTRICT";
		$this->db->query($fk_expense);
	}
}
