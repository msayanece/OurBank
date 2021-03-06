<?php
/*
############################################################################
#  This file is part of OurBank.
############################################################################
#  OurBank is free software: you can redistribute it and/or modify
#  it under the terms of the GNU Affero General Public License as
#  published by the Free Software Foundation, either version 3 of the
#  License, or (at your option) any later version.
############################################################################
#  This program is distributed in the hope that it will be useful,
#  but WITHOUT ANY WARRANTY; without even the implied warranty of
#  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
#  GNU Affero General Public License for more details.
############################################################################
#  You should have received a copy of the GNU Affero General Public License
#  along with this program.  If not, see <http://www.gnu.org/licenses/>.
############################################################################
*/

class Psdetails_Model_Savingsdeposit extends Zend_Db_Table 
{
    protected $_name = 'ourbank_accounts';

    public function getbalance($acc){
        $select = $this->select()
                ->setIntegrityCheck(false)
                ->join(array('a' => 'ourbank_accounts'),array('id'),array(null))
                ->join(array('b' => 'ourbank_savings_transaction'),'a.id = b.account_id',array('sum(amount_to_bank) - sum(amount_from_bank) as Balance'))
                ->where('a.account_number = ?',$acc);
        $result = $this->fetchAll($select);
        return $result->toArray(); // return get Balance
// die($select->__toString($select));
    }  

    public function transaction($acc) 
    {
         $select = $this->select()
                ->setIntegrityCheck(false)
                ->join(array('a' => 'ourbank_accounts'),array('id'),array('id'))
                ->join(array('b' => 'ourbank_group_savingstransaction'),'a.id = b.account_id')
                ->join(array('c' => 'ourbank_user'),'c.id = b.transacted_by',array('name as createdby'))
                ->where('a.account_number = ?',$acc)
                ->order('b.transaction_id desc');
        $result = $this->fetchAll($select);
        return $result->toArray(); // return get Transaction details
// die($select->__toString($select));
    }  

    public function getCreditbalance($acc) 
    {
         $select = $this->select()
                ->setIntegrityCheck(false)
                ->join(array('a' => 'ourbank_accounts'),array('id'),array('id'))
                ->join(array('b' => 'ourbank_group_savingstransaction'),'a.id = b.account_id',array('sum(transaction_amount) as Credit'))
                ->where('b.transaction_type = 1')
                ->where('a.account_number = ?',$acc);
        $result = $this->fetchAll($select);
        return $result->toArray(); // return Credit details
// die($select->__toString($select));
    }    

   public function getDebitbalance($acc) 
    {
         $select = $this->select()
                ->setIntegrityCheck(false)
                ->join(array('a' => 'ourbank_accounts'),array('id'),array('id'))
                ->join(array('b' => 'ourbank_group_savingstransaction'),'a.id = b.account_id',array('sum(transaction_amount) as Debit'))
                ->where('b.transaction_type = 2')
                ->where('a.account_number = ?',$acc);
        $result = $this->fetchAll($select);
        return $result->toArray(); // return Credit details
// die($select->__toString($select));
    }  


}