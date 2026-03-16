<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HomePage\AdditionalSection;
use App\Models\HomePage\AdditionalSectionContent;
use App\Models\HomePage\Section;
use App\Models\Language;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Mews\Purifier\Facades\Purifier;

class AdditionSectionController extends Controller
{
    public function index(Request $request)
    {
        $lang = Language::where('code', $request->language)->firstOrFail();
        $information['langs'] = Language::all();

        $information['sections'] = AdditionalSection::join('additional_section_contents', 'additional_section_contents.addition_section_id', '=', 'additional_sections.id')
            ->where('language_id', $lang->id)
            ->where('page_type', 'home')
            ->select('additional_sections.*', 'additional_section_contents.section_name')
            ->get();
        $information['positionValues'] = config('section_positions');


        return view('admin.home-page.additional-section.index', $information);
    }

    public function create(Request $request)
    {
        $information['positions'] = config('section_positions');
        $information['language'] = Language::where('is_default', 1)->first();
        $information['languages'] = Language::all();
        $information['page_type'] = 'home';
        return view('admin.home-page.additional-section.create', $information);
    }
    public function store(Request $request)
    {
        $rules = [
            'position' => 'required',
            'page_type' => 'required',
            'serial_number' => 'required',
        ];
        $languages = Language::get();
        $messages = [];
        foreach ($languages as $language) {
            if ($language->is_default == 1) {
                $rules[$language->code . '_section_name'] = 'required';
                $rules[$language->code . '_content'] = 'required';

                $messages[$language->code . '_section_name.required'] = __('The section name is required for') . ' ' . $language->name . ' ' . __('language');
                $messages[$language->code . '_content.required'] =
                    __('The section content is required for') . ' ' . $language->name . ' ' . __('language');
            } else {
                if (!is_null($request[$language->code . '_section_name']) || !is_null($request[$language->code . '_content'])) {
                    $rules[$language->code . '_section_name'] = 'required';
                    $rules[$language->code . '_content'] = 'required';

                    $messages[$language->code . '_section_name.required'] = __('The section name is required for') . ' ' . $language->name . ' ' . __('language');
                    $messages[$language->code . '_content.required'] =
                        __('The section content is required for') . ' ' . $language->name . ' ' . __('language');
                }
            }
        }

        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->getMessageBag()
            ], 400);
        }

        $languages = Language::all();
        $section = AdditionalSection::create($request->all());


        foreach ($languages as $language) {
            $code = $language->code;
            if ($language->is_default == 1 || $request->filled($code . '_section_name') || $request->filled($code . '_content')) {
                $content = new AdditionalSectionContent();
                $content->language_id = $language->id;
                $content->addition_section_id = $section->id;
                $content->section_name = $request[$code . '_section_name'];
                $content->content = Purifier::clean($request[$code . '_content'], 'youtube');
                $content->save();
            }
        }

        $section = Section::first();
        $arr = json_decode($section->additional_section_status, true);
        $arr["$section->id"] = "1";

        $section->additional_section_status = json_encode($arr);
        $section->save();

        Session::flash('success', __('Created Successfully'));

        return response()->json([
            'status' => 'success',
        ]);
    }
    public function edit($id, Request $request)
    {
        $information['positions'] = config('section_positions');
        $information['languages'] = Language::all();
        $information['language'] = Language::where('is_default', 1)->first();
        $information['section'] = AdditionalSection::where('page_type', 'home')->where('id', $id)->firstOrFail();
        return view('admin.home-page.additional-section.edit', $information);
    }

    public function update(Request $request, $id)
    {
        $rules = [
            'position' => 'required',
            'page_type' => 'required',
            'serial_number' => 'required',
        ];
        $languages = Language::get();
        $messages = [];
        foreach ($languages as $language) {
            if ($language->is_default == 1) {
                $rules[$language->code . '_section_name'] = 'required';
                $rules[$language->code . '_content'] = 'required';
                $messages[$language->code . '_section_name.required'] = __('The section name is required for') . ' ' . $language->name . ' ' . __('language');
                $messages[$language->code . '_content.required'] =
                    __('The section content is required for') . ' ' . $language->name . ' ' . __('language');
            } else {
                if (!is_null($request[$language->code . '_section_name']) || !is_null($request[$language->code . '_content'])) {
                    $rules[$language->code . '_section_name'] = 'required';
                    $rules[$language->code . '_content'] = 'required';

                    $messages[$language->code . '_section_name.required'] = __('The section name is required for') . ' ' . $language->name . ' ' . __('language');
                    $messages[$language->code . '_content.required'] =
                        __('The section content is required for') . ' ' . $language->name . ' ' . __('language');
                }
            }
        }

        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->getMessageBag()
            ], 400);
        }

        $section = AdditionalSection::findOrFail($id);
        $section->position = $request->position;
        $section->page_type = $request->page_type;
        $section->serial_number = $request->serial_number;
        $section->save();

        $languages = Language::all();

        foreach ($languages as $language) {
            $content = AdditionalSectionContent::where('addition_section_id', $id)->where('language_id', $language->id)->first();
            if (empty($content)) {
                $content = new AdditionalSectionContent();
            }
            $code = $language->code;
            if ($language->is_default == 1 || $request->filled($code . '_section_name') || $request->filled($code . '_content')) {

                $content = AdditionalSectionContent::firstOrNew([
                    'addition_section_id' => $section->id,
                    'language_id' => $language->id
                ]);
                $content->section_name = $request[$code . '_section_name'];
                $content->content = Purifier::clean($request[$code . '_content'], 'youtube');
                $content->save();
            }
        }

        Session::flash('success', __('Updated Successfully'));
        return response()->json([
            'status' => 'success',
        ]);
    }

    public function delete($id)
    {
        $section = AdditionalSection::findOrFail($id);
        $contents = AdditionalSectionContent::where('addition_section_id', $id)->get();
        foreach ($contents as $content) {
            $content->delete();
        }
        $section->delete();
        return redirect()->back()->with('success', __('Deleted Successfully'));
    }

    public function bulkdelete(Request $request)
    {
        $ids = $request->ids;

        foreach ($ids as $id) {
            $page = AdditionalSection::query()->findOrFail($id);

            $contents = AdditionalSectionContent::where('addition_section_id', $id)->get();

            foreach ($contents as $pageContent) {
                $pageContent->delete();
            }

            $page->delete();
        }
        Session::flash('success', __('Deleted Successfully'));
        return Response::json(['status' => 'success'], 200);
    }
}
