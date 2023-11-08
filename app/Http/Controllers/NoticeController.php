<?php

namespace App\Http\Controllers;

use App\Http\Requests\NoticeRequest;
use App\Http\Requests\NoticeStoreRequest;
use App\Models\Notice;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Inertia\Response;

class NoticeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Inertia\Response;
     */
    public function index(): Response
    {
        return Inertia::render('Notices/Index', [
            'notices' => Notice::latest()->get(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return Inertia::render('Notices/CreateEdit');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(NoticeRequest $request): RedirectResponse
    {
        $notice = new Notice();
        $notice->title = $request->title;
        $notice->content = $request->content;
        $notice->distribution_start_date = $request->distribution_start_date;
        $notice->distribution_end_date = $request->distribution_end_date;
        $notice->create_user_id = $request->user()->id;
        $notice->last_edit_user_id = $request->user()->id;

        $path = $this->storeImage($request);
        if ($path) {
            $notice->image = $path;
        }

        $notice->save();

        return redirect(route('notices.index'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Notice  $notice
     * @return \Illuminate\Http\Response
     */
    public function show(Notice $notice)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Notice  $notice
     * @return \Illuminate\Http\Response
     */
    public function edit(Notice $notice)
    {
        return Inertia::render('Notices/CreateEdit', [
            'notice' => $notice,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Notice  $notice
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Notice $notice): RedirectResponse
    {
        $noticeUpd = Notice::find($notice->id);
        $noticeUpd->title = $request->title;
        $noticeUpd->content = $request->content;
        $noticeUpd->distribution_start_date = $request->distribution_start_date;
        $noticeUpd->distribution_end_date = $request->distribution_end_date;
        $noticeUpd->last_edit_user_id = $request->user()->id;
        $noticeUpd->updated_at = now();

        $path = $this->storeImage($request);
        if ($path) {
            $this->deleteImage($notice->image);
            $noticeUpd->image = $path;
        }

        $noticeUpd->save();

        return redirect(route('notices.index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Notice  $notice
     * @return \Illuminate\Http\Response
     */
    public function destroy(Notice $notice): RedirectResponse
    {
        $this->deleteImage($notice->image);
        $notice->delete();

        return redirect(route('notices.index'));
    }

    /**
     * Update Public Mode
     */
    public function updatePublic(Notice $notice): RedirectResponse
    {
        $notice->public_flag = $notice->public_flag == 1 ? 0 : 1;
        $notice->save();

        return redirect(route('notices.index'));
    }

    protected function storeImage(Request $request)
    {
        if ($request->hasfile('image')) {
            $file = $request->file('image');
            $filename = date('YmdHi') . $file->getClientOriginalName();;
            $dir = 'upload/images';
            $path = $file->storeAs($dir, $filename);

            return $path;
        }
        return null;
    }

    protected function deleteImage(String $imagePath)
    {
        if ($imagePath) {
            Storage::delete($imagePath);
        }
    }
}
