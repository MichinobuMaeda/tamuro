<?php

namespace Tests\Unit\Services;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

use DateTime;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Tests\Unit\UnitTestHelper;
use App\Services\UsersService;
use App\AuthProvider;

class UsersServiceTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Set up for each test.
     */
    protected function setUp()
    {
        parent::setUp();
        $this->helper = new UnitTestHelper($this);
        $this->helper->prepareGroupsAndUsers();
    }

    /**
     * The test of method list().
     *
     * @return void
     */
    public function testList()
    {
        $svc = new UsersService();

        $this->user01->name = 'user 01';
        $this->user01->save();

        Auth::login($this->user06);
        $ret = $svc->list()->toArray();
        $this->assertEquals(9, count($ret));
        $this->assertEquals($this->user00->id, $ret[0]['id']);
        $this->assertEquals($this->user01->id, $ret[8]['id']);

        $ret = $svc->list('name', 'desc')->toArray();
        $this->assertEquals(9, count($ret));
        $this->assertEquals($this->user01->id, $ret[0]['id']);
        $this->assertEquals($this->user00->id, $ret[8]['id']);

        $ret = $svc->list('id')->toArray();
        $this->assertEquals(9, count($ret));
        $this->assertEquals($this->user00->id, $ret[0]['id']);
        $this->assertEquals($this->user08->id, $ret[8]['id']);

        Auth::login($this->user01);
        $ret = $svc->list()->toArray();
        $this->assertEquals(2, count($ret));
        $this->assertEquals($this->user04->id, $ret[0]['id']);
        $this->assertEquals($this->user01->id, $ret[1]['id']);

        $ret = $svc->list('name', 'desc')->toArray();
        $this->assertEquals(2, count($ret));
        $this->assertEquals($this->user01->id, $ret[0]['id']);
        $this->assertEquals($this->user04->id, $ret[1]['id']);

        $ret = $svc->list('id')->toArray();
        $this->assertEquals(2, count($ret));
        $this->assertEquals($this->user01->id, $ret[0]['id']);
        $this->assertEquals($this->user04->id, $ret[1]['id']);

        Auth::login($this->user02);
        $ret = $svc->list()->toArray();
        $this->assertEquals(0, count($ret));
    }

    /**
     * The test of method listLoginMethods().
     *
     * @return void
     */
    public function testListLoginMethods()
    {
        $svc = new UsersService();

        $ret = $svc->listLoginMethods();
        $this->assertCount(0, $ret);

        AuthProvider::create([
            'user_id' => $this->user01->id,
            'provider' => 'google',
            'secret' => 'secret01',
        ]);

        $ret = $svc->listLoginMethods();
        $this->assertCount(1, $ret);
        $this->assertContains(''.$this->user01->id."\tgoogle", $ret);

        AuthProvider::create([
            'user_id' => $this->user01->id,
            'provider' => 'facebook',
            'secret' => 'secret02',
        ]);

        $ret = $svc->listLoginMethods();
        $this->assertCount(2, $ret);
        $this->assertContains(''.$this->user01->id."\tgoogle", $ret);
        $this->assertContains(''.$this->user01->id."\tfacebook", $ret);

        AuthProvider::create([
            'user_id' => $this->user02->id,
            'provider' => 'yahoo_jp',
            'secret' => 'secret03',
        ]);

        $ret = $svc->listLoginMethods();
        $this->assertCount(3, $ret);
        $this->assertContains(''.$this->user01->id."\tgoogle", $ret);
        $this->assertContains(''.$this->user01->id."\tfacebook", $ret);
        $this->assertContains(''.$this->user02->id."\tyahoo_jp", $ret);
    }

    /**
     * The test of method listUserLoginMethods().
     *
     * @return void
     */
    public function testListUserLoginMethods()
    {
        $svc = new UsersService();

        $ret = $svc->listUserLoginMethods($this->user01->id);
        $this->assertCount(0, $ret);
        $ret = $svc->listUserLoginMethods($this->user02->id);
        $this->assertCount(0, $ret);

        AuthProvider::create([
            'user_id' => $this->user01->id,
            'provider' => 'google',
            'secret' => 'secret01',
        ]);

        $ret = $svc->listUserLoginMethods($this->user01->id);
        $this->assertCount(1, $ret);
        $this->assertContains("google", $ret);
        $ret = $svc->listUserLoginMethods($this->user02->id);
        $this->assertCount(0, $ret);

        AuthProvider::create([
            'user_id' => $this->user01->id,
            'provider' => 'facebook',
            'secret' => 'secret02',
        ]);

        $ret = $svc->listUserLoginMethods($this->user01->id);
        $this->assertCount(2, $ret);
        $this->assertContains("google", $ret);
        $this->assertContains("facebook", $ret);
        $ret = $svc->listUserLoginMethods($this->user02->id);
        $this->assertCount(0, $ret);

        AuthProvider::create([
            'user_id' => $this->user02->id,
            'provider' => 'yahoo_jp',
            'secret' => 'secret03',
        ]);

        $ret = $svc->listUserLoginMethods($this->user01->id);
        $this->assertCount(2, $ret);
        $this->assertContains("google", $ret);
        $this->assertContains("facebook", $ret);
        $ret = $svc->listUserLoginMethods($this->user02->id);
        $this->assertCount(1, $ret);
        $this->assertContains("yahoo_jp", $ret);
    }

    /**
     * The test of method savePreferenceLoginEmail().
     *
     * @return void
     */
    public function testSavePreferenceLoginEmail()
    {
        $svc = new UsersService();

        Auth::login($this->user01);

        $svc->savePreferenceLoginEmail(null);
        $this->user01->refresh();
        $this->assertNull($this->user01->email);

        $svc->savePreferenceLoginEmail('abc@def.ghi');
        $this->user01->refresh();
        $this->assertEquals('abc@def.ghi', $this->user01->email);
    }

    /**
     * The test of method savePreferenceLoginPassword().
     *
     * @return void
     */
    public function testSavePreferenceLoginPassword()
    {
        $svc = new UsersService();

        Auth::login($this->user01);

        $password0 = $this->user01->password;

        $svc->savePreferenceLoginPassword(null);
        $this->user01->refresh();
        $password1 = $this->user01->password;
        $this->assertNotEquals($password0, $password1);

        $svc->savePreferenceLoginPassword('password1234567890');
        $this->user01->refresh();
        $password2 = $this->user01->password;
        $this->assertNotEquals($password0, $password2);
        $this->assertNotEquals($password1, $password2);
    }
}
