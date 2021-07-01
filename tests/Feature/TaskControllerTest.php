<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Task;
use Auth;

class TaskControllerTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    // public function test_example()
    // {
    //     $response = $this->get('/');

    //     $response->assertStatus(200);
    // }

    /** @test */
    public function authenticated_users_can_view_all_tasks()
    {
        //Given we have an authenticated user
        $user = User::factory()->create([
            'password' => bcrypt('i-love-laravel'),
            'role' => 'user'
        ]);  

        //And a task which is created by the user
        $task = Task::factory()->create();

        //$response = $this->actingAs($user)->get('/user/tasks');
        $response = $this->actingAs($user)->get(route('user.tasks.index'));
            
        $response->assertStatus(200);
    }   

    /** @test */
    public function authenticated_users_can_read_single_task()
    {
        //Given we have an authenticated user
        $user = User::factory()->create([
            'password' => bcrypt('i-love-laravel'),
            'role' => 'user'
        ]);
    
        //And a task which is created by the user
        $task = Task::factory()->create(['user_id' => $user]);
    
        //$response = $this->actingAs($user)->get('/user/tasks/show'.$task->id);
        $response = $this->actingAs($user)->get(route('user.tasks.show', $task->id));
    
        $response->assertStatus(200);
    }

    /** @test */
    public function authenticated_users_can_create_a_new_task()
    {
        //Given we have an authenticated user
        $user = User::factory()->create([
            'password' => bcrypt('i-love-laravel'),
            'role' => 'user'
        ]);
 
        //And a task which is created by the user
        $task = Task::factory()->create(['user_id' => $user->id]);
     
        // $response = $this->actingAs($user)->post('task.create', $task->toArray());
        $response = $this->actingAs($user)->post(route('user.tasks.create', $task->toArray()));
     
         //It gets stored in the database
         //It will return true if: expected.equals( actual ) returns true.
        $this->assertEquals(1, Task::all()->count());
    }

    /** @test */
    public function unauthenticated_users_cannot_create_a_new_task()
    {
        //Given we have a task object
        $task = Task::factory()->make();
    
        //When unauthenticated user submits post request to create task endpoint
        // He should be redirected to login page
        // $this->get('/user/tasks/create', $task->toArray())
        //          ->assertRedirect('/login');

        $this->get(route('user.tasks.create', $task->toArray()))
                 ->assertRedirect('/login');
    }

    /** @test */
    public function a_task_requires_a_name()
    {
        //Given we have an authenticated user
        $user = User::factory()->create([
            'password' => bcrypt('i-love-laravel'),
            'role' => 'user'
        ]);
    
        //Make a name to null
        $task = Task::factory()->make(['name' => null]);
    
        // $this->actingAs($user)->post('/user/tasks', $task->toArray())
        //             ->assertSessionHasErrors('name');
        $this->actingAs($user)->post(route('user.tasks.index', $task->toArray()))
                    ->assertSessionHasErrors('name');
    }

    /** @test */
    public function a_task_requires_a_description()
    {
        //Given we have an authenticated user
        $user = User::factory()->create([
            'password' => bcrypt('i-love-laravel'),
            'role' => 'user'
        ]);
    
        //Make a name to null
        $task = Task::factory()->make(['description' => null]);
    
        // $this->actingAs($user)->post('/user/tasks', $task->toArray())
        //             ->assertSessionHasErrors('name');
        $this->actingAs($user)->post(route('user.tasks.index', $task->toArray()))
                    ->assertSessionHasErrors('description');
    }

    /** @test */
    public function a_task_requires_a_user_id()
    {
        //Given we have an authenticated user
        $user = User::factory()->create([
            'password' => bcrypt('i-love-laravel'),
            'role' => 'user'
        ]);
    
        //Make a user id to null
        $task = Task::factory()->make(['user_id' => null]);
    
        // $this->actingAs($user)->post('/user/tasks', $task->toArray())
        //             ->assertSessionHasErrors('name');
        $this->actingAs($user)->post(route('user.tasks.index', $task->toArray()))
                    ->assertSessionHasErrors('user_id');
    }

     /** @test */
     public function authorized_user_can_update_task()
     {
         //Given we have an authenticated user
         $user = User::factory()->create([
             'password' => bcrypt('i-love-laravel'),
             'role' => 'user'
         ]);
 
         //And a Task which is created by the user
         $task = Task::factory()->create(['user_id' => $user->id, 'name' => "Coding"]);
 
         //When the user hit's the endpoint to update the task
        //  $response = $this->actingAs($user)->put('/user/tasks'.$task->id, $task->toArray());
        $response = $this->actingAs($user)->put(route('user.tasks.update', $task->id, $task->toArray()));
 
         //The task should be updated in the database.
         $this->assertDatabaseHas('tasks',['id'=> $task->id , 'name' => 'Coding']);
     }

     /** @test */
    public function unauthorized_user_cannot_update_task()
    {
        //Given we have an authenticated user
        $user = User::factory()->create([
            'password' => bcrypt('i-love-laravel'),
            'role' => 'user'
        ]);

        //And a task which is not created by the user
        $task = Task::factory()->create();

        //When the user hit's the endpoint to update the task
        // $response = $this->actingAs($user)->put('/user/tasks/'.$task->id, $task->toArray());
        $response = $this->actingAs($user)->post(route('user.tasks.update', $task->id, $task->toArray()));

        //We should expect a 403 error
        $response->assertStatus(403);
    }

    /** @test */
    public function authorized_user_can_delete_task()
    {
        //Given we have an authenticated user
        $user = User::factory()->create([
            'password' => bcrypt('i-love-laravel'),
            'role' => 'user'
        ]);

        //And a task which is created by the user
        $task = Task::factory()->create(['user_id' => $user]);

        //When the user hit's the endpoint to delete the family
        $response = $this->actingAs($user)->delete('/user/tasks/{task}/delete'.$task->id);

        //The task should be deleted from the database
        $this->assertDatabaseHas('tasks',['id'=> $task->id]);
    }

    /** @test */
    public function unauthorized_user_cannot_delete_task()
    {
        //Given we have an authenticated user
        $user = User::factory()->create([
            'password' => bcrypt('i-love-laravel'),
            
        ]);

        //And a task which is not created by the user
        $task = Task::factory()->create();

        //When the user hit's the endpoint to delete the task
        // $response = $this->actingAs($user)->delete('/user/tasks/'.$task->id);
        $response = $this->actingAs($user)->delete(route('user.tasks.destroy', $task->id));

        //We should expect a 403 error
        $response->assertStatus(403);
    }

}
