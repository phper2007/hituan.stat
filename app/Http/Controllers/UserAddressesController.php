<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserAddress;
use App\Http\Requests\UserAddressRequest;
use GuzzleHttp\Client;

class UserAddressesController extends Controller
{
    public function index(Request $request)
    {
        $addresses = (new UserAddress())->orderBy('id', 'desc')->get();
        return view('user_addresses.index', [
            'addresses' => $addresses,
        ]);
    }


    public function standard(Request $request)
    {
        $content = $request->input('content', '');
        $responseContent = '';
        $errors = [];
        $address = new \stdClass();

        if($request->isMethod('post'))
        {
            $url = config('external.sf.standardUrl');

            $client = new Client();

            $res = $client->request('POST', $url . urlencode($content));
            $responseContent = $res->getBody();
            $sfArr = json_decode($responseContent, true);
            $data = $sfArr['obj'];

            if(!$data['personalName'])
            {
                $errors[] = '无姓名';
            }
            if(!$data['telephone'])
            {
                $errors[] = '无电话';
            }
            if(!$data['province'])
            {
                $errors[] = '省份错误';
            }
            if(!$data['city'])
            {
                $errors[] = '城市错误';
            }
            if(!$data['area'])
            {
                $errors[] = '区、县错误';
            }
            if(!$data['site'])
            {
                $errors[] = '无详细地址';
            }

            if($data)
            {
                //避免数字结尾
                if(preg_match('/^.*[0-9]$/', $data['site']))
                {
                    $data['site'] .= '室';
                }

                $address->id = '';
                $address->province = $data['province'];
                $address->city = $data['city'];
                $address->district = $data['area'];
                $address->address = $data['site'];
                $address->contact_name = $data['personalName'];
                $address->contact_phone = $data['telephone'];

                return view('user_addresses.create_and_edit', ['address' => $address]);
            }
        }

        return view('user_addresses.standard', compact('responseContent', 'content', 'errors'));
    }

    public function create()
    {
        return view('user_addresses.create_and_edit', ['address' => new UserAddress()]);
    }

    public function store(UserAddressRequest $request)
    {
        $request->user()->addresses()->create($request->only([
            'province',
            'city',
            'district',
            'address',
            'contact_name',
            'contact_phone',
        ]));

        return redirect()->route('user_addresses.index');
    }

    public function edit(UserAddress $user_address)
    {
        return view('user_addresses.create_and_edit', ['address' => $user_address]);
    }

    public function update(UserAddress $user_address, UserAddressRequest $request)
    {
        $user_address->update($request->only([
            'province',
            'city',
            'district',
            'address',
            'contact_name',
            'contact_phone',
        ]));

        return redirect()->route('user_addresses.index');
    }

    public function destroy(UserAddress $user_address)
    {
        $user_address->delete();
        // 把之前的 redirect 改成返回空数组
        return [];
    }
}
