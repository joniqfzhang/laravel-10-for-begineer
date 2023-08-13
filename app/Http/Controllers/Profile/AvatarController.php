<?php

namespace App\Http\Controllers\Profile;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use OpenAI\Laravel\Facades\OpenAI;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\UpdateAvatarRequest;

class AvatarController extends Controller
{
    public function generate(Request $request)
    {
        $result = OpenAI::images()->create([
            // "prompt" => "A cute baby bird " . auth()->user()->name,
            // "prompt" => "create avata for a lady , chinese born, with cool style animated in tech with  " . auth()->user()->name,
            "prompt" => "generate an single head avatar icon for a user", //. auth()->user()->name,
            "n" => 1,
            "size" => "256x256",
        ]);

        if ($oldAvatar = $request->user()->avatar) {
            Storage::disk('public')->delete($oldAvatar);
        }

        $contents = file_get_contents($result->data[0]->url);

        $filename = Str::random(25);

        // dd($contents);
        Storage::disk('public')->put("avatars/$filename.jpg", $contents);

        auth()->user()->update(['avatar' => "avatars/$filename.jpg"]);

        return redirect(route('profile.edit'))->with('message', 'Avatar is updated');

        // $result = OpenAI::images()->create([
        //     // "prompt" => "A cute baby bird " . auth()->user()->name,
        //     // "prompt" => "create avata for a lady , chinese born, with cool style animated in tech with  " . auth()->user()->name,
        //     "prompt" => "create avata for a lady age 49s, chinese, happy, dark hair, looks young cool style animated in tech with  " . auth()->user()->name,
        //     "n" => 1,
        //     "size" => "256x256",
        // ]);
        // dd($result);
        // return response(['url' => $result->data[0]->url]);
        // dd($result->data[0]->url);
        // echo $result['choices'][0]['text']; // an open-source, widely-used, server-side scripting language.
    }

    public function update(UpdateAvatarRequest $request)
    {
        // move validatation into UpdatedAvatarRequest now.
        // $request->validate([
        //     // 'avatar' => 'required|image',
        //     'avatar' => ['required', 'image']
        // ]);
        // dd($request->input('_token'));
        // dd($request->input('avatar'));
        // dd($request->all());
        // dd($request->file('avatar'));
        //dd($request->file('avatar')->store('avatars'));

        // store avator
        // $path = $request->file('avatar')->store('avatars','public');
        $path = Storage::disk('public')->put('avatars', $request->file('avatar'));

        if ($oldAvatar = $request->user()->avatar) {
            Storage::disk('public')->delete($oldAvatar);
        }
        auth()->user()->update(['avatar' => $path]);
        // dd(auth()->user()->fresh()); 

        return redirect(route('profile.edit'))->with('message', ' Avatar is updated');
        // return back()->with(['message' => 'Success']);
    }
}
