<?php

namespace Tests\Unit;

use Tests\TestCase;

class CategoryTest extends TestCase
{
    /**
     * Test ID: Category-001
     * Description: Check if we can access the get all categories api
     * Precondition: None
     * Test Steps:
     *  1. Hit the get all categories api
     *  2. Check if the response status is 200
     * Test Data: None
     * Expected Result: The response status should be 200
     * Actual Result: reponse returned 200
     * Status: PASSED
     * Remark: None
     *
     */
    public function test_if_we_can_access_get_all_categories_api(): void
    {
        $response = $this->get('/api/categories');
        $response->assertStatus(200);
    }

    /**
     * Test ID: Category-002
     * Description: Check if we can create a category using the api
     * Precondition: None
     * Test Steps:
     *  1. Hit the POST category api
     *  2. Check if the resonse status is 201
     * Test Data:
     *          1. name: test_category_01
     *          2. name: test_categoryy_02
     *          3. name: test_category_03
     * Expected Result: The response status should be 201
     * Actual Result: response returned 201
     * Status: PASSED
     * Remark: None
     *
     */
    public function test_if_we_can_access_create_category_api(): void
    {
        $response = $this->postJson('/api/categories', [
            "name" => "test_category_01"
        ]);
        $response->assertStatus(201)->assertJson(['name' => "test_category_01"]);

        $response = $this->postJson('/api/categories', [
            "name" => "test_category_02"
        ]);
        $response->assertStatus(201)->assertJson(['name' => "test_category_02"]);

        $response = $this->postJson('/api/categories', [
            "name" => "test_category_03"
        ]);
        $response->assertStatus(201)->assertJson(['name' => "test_category_03"]);
    }

     /**
     * Test ID: Category-003
     * Description: Check if we can access the get a category by its id api
     * Precondition: None
     * Test Steps:
     *  1. Hit the get category by id api
     *  2. Check if the resonse status is 200
     * Test Data: None
     * Expected Result: The response status should be 200
     * Actual Result: response returned 200
     * Status: PASSED
     * Remark: None
     *
     */
    public function test_if_we_can_access_get_a_category_by_id_api(): void
    {
        $response = $this->get('/api/categories/1');
        $response->assertStatus(200)->assertJson(["id" => 1]);
    }


     /**
     * Test ID: Category-004
     * Description: Check if we can update a category by its id using the api
     * Precondition: None
     * Test Steps:
     *  1. Hit the update category api by sending a PATCH request
     *  2. Check if the resonse status is 200
     *  3. Check if the data is updated
     * Test Data: name: test_category_01_updated
     * Expected Result: The response status should be 200, and data is updated
     * Actual Result: response returned 200, and data was updated
     * Status: PASSED
     * Remark: None
     *
     */
    public function test_if_we_can_access_update_a_category_by_id_api(): void
    {
        $response = $this->patch('/api/categories/2', ["name" => "test_category_updated"]);
        $response->assertStatus(200)->assertJson([
            "id" => 2,
            "name" => "test_category_updated"
        ]);
    }

      /**
     * Test ID: Category-005
     * Description: Check if we can delete a category by its id using the api
     * Precondition: None
     * Test Steps:
     *  1. Hit the delete category api by sending a DELETE request
     *  2. Check if the resonse status is 200
     *  3. Get the deleted category
     *  4. Check if resource is empty
     * Test Data: id: 1,
     * Expected Result: The response status should be 200
     * Actual Result: status code: 200
     * Status: PASSED
     * Remark: None
     *
     */
    public function test_if_we_can_access_delete_a_category_by_id_api(): void
    {
        $response = $this->delete('/api/categories/1');
        $response->assertStatus(200)->assertJson(["id" => 1]);

        $request = $this->get('/api/categories/1');
        $request->assertStatus(200)->assertDontSee(["id" => 1]);
    }
}
