<?php

namespace Patrikgrinsvall\LaravelBankid\Contracts;
class BankidInterface {
    */
    public function create($collection, Array $document);

    /**
     * Update a Document
     *
     * @param string $collection Collection/Table Name
     * @param mix    $id         Primary Id
     * @param array  $document   Document
     * @return boolean
     */
    public function update($collection, $id, Array $document);

    /**
     * Delete a Document
     *
     * @param string $collection Collection/Table Name
     * @param mix    $id         Primary Id
     * @return boolean
     */
    public function delete($collection, $id);

    /**
     * Search Document(s)
     *
     * @param string $collection Collection/Table Name
     * @param array  $criteria   Key-value criteria
     * @return array
     */
    public function find($collection, Array $criteria);
}
