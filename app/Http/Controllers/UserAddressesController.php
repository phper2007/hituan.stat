<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserAddress;
use App\Models\Express;
use App\Http\Requests\UserAddressRequest;
use GuzzleHttp\Client;
use App\Librarys\AipOcr;
use Illuminate\Support\Facades\Storage;

class UserAddressesController extends Controller
{
    public function index(Request $request)
    {
        $keywords = $request->input('keywords', '');

        $query = (new UserAddress())->orderBy('contact_name', 'asc');
        if($keywords)
        {
            $query->where(function ($query) use ($keywords){
                $query->where('contact_name', 'like', "%{$keywords}%")
                    ->orWhere('contact_phone', 'like', "%{$keywords}%")
                    ->orWhere('address', 'like', "%{$keywords}%");
            });
        }

        $addresses = $query->paginate(20);

        return view('user_addresses.index', [
            'addresses' => $addresses,
            'keywords' => $keywords,
        ]);
    }

    public function ocr(Request $request)
    {
        $content = $request->input('content', '');
        $errors = [];
        $address = new \stdClass();

        if($request->isMethod('post') && $request->hasFile('file'))
        {
            $config = config('external.baidu');

            $client = new AipOcr($config['app_id'], $config['api_key'], $config['secret_key']);

            $path = $request->file->store('address');

            $result = $client->basicAccurate(Storage::get($path));

            $result = array_column($result['words_result'], 'words');

            $content = implode("\n", $result);

            return view('user_addresses.standard', compact('content','errors'));
        }

        return view('user_addresses.ocr', compact('content', 'errors'));
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

            if(!$errors)
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
        $data = $request->only([
            'province',
            'city',
            'district',
            'address',
            'contact_name',
            'contact_phone',
        ]);

        $hasData = UserAddress::where('province', $data['province'])
            ->where('city', $data['city'])
            ->where('district', $data['district'])
            ->where('address', $data['address'])
            ->where('contact_phone', $data['contact_phone'])
            ->first();

        if($hasData)
        {
            if($request->exists('saveAndOrder'))
            {
                return redirect()->route('orders.create', ['user_address' => $hasData->id]);
            }
            else
            {
                return redirect()->route('user_addresses.index', ['keywords' => $hasData->contact_phone]);
            }
        }

        $result = $request->user()->addresses()->create($data);

        if($request->exists('saveAndOrder'))
        {
            return redirect()->route('orders.create', ['user_address' => $result->id]);
        }
        else
        {
            return redirect()->route('user_addresses.index');
        }
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

    public function clear()
    {
        (new Express())->where('id', '>', 0)->update(['is_msg' => 0]);
        (new UserAddress())->where('id', '>', 0)->update(['msg_count' => 0]);

        return redirect()->route('user_addresses.index');
    }
}
