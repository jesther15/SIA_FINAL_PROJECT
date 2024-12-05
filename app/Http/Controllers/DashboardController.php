<?php

namespace App\Http\Controllers;

use App\Models\Resume;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class DashboardController extends Controller
{
    public function index()
    {
        $resumes = Resume::orderBy('created_at', 'desc')->get();
        $user = Auth::user()->email;
        $userName = Auth::user()->name;

        $counts = [
            'interview' => 0,
            'hired' => 0,
            'others' => 0,
            'total' => 0
        ];

        foreach ($resumes as $resume) {
            if (!empty($resume['applications'])) {
                foreach ($resume['applications'] as $application) {
                    if ($application['status'] === 'hired') {
                        $counts['hired']++;
                    } elseif ($application['status'] === 'interview') {
                        $counts['interview']++;
                    } else {
                        $counts['others']++;
                    }
                    $counts['total']++;
                }
            }
        }

        return view('dashboard', compact('resumes', 'user', 'userName', 'counts'));
    }

    public function create(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required',
                'email' => 'required',
                'contact' => 'required',
                'address' => 'required',
            ]);

            if ($request->hasFile('_image')) {
                $imageName = time() . '.' . $request->_image->extension();
                $request->_image->move(public_path('images'), $imageName);
                $request->merge(['image' => $imageName]);
            } else {
                $request->merge(['image' => 'default-avatar.jpg']);
            }
            if (empty($request->skills)) {
                $request->merge(['skills' => []]);
            }

            $educations = [];
            foreach (['elementary', 'highschool', 'senior', 'college',] as $key) {
                $education_array = [];

                foreach ($request->{$key . "_education"} as $educ) {
                    if (isset($educ['name']) && isset($educ['location']) && isset($educ['date_graduated'])) {
                        $education_array[] = $educ;
                    }
                }

                if (!empty($education_array)) {
                    $educations[$key] = $education_array;
                }
            }
            if (!empty($educations)) {
                $request->merge(['education' => $educations]);
            }


            Resume::create($request->all());

            return redirect()->route('dashboard.index')->with('success', 'Resume created successfully.');
        } catch (\Throwable $th) {
            return redirect()->back()->withErrors(['error' => $th->getMessage()])->withInput();
        }
    }

    public function update(Request $request, $id)
    {
        try {
            if ($request->hasFile('_image')) {
                $resume = Resume::find($id);
                if ($resume->image != "default-avatar.jpg") {
                    $oldImagePath = public_path('images') . '/' . basename($resume->image);
                    if (file_exists($oldImagePath)) {
                        unlink($oldImagePath);
                    }
                }

                $imageName = time() . '.' . $request->_image->extension();
                $request->_image->move(public_path('images'), $imageName);
                $request->merge(['image' => $imageName]);
            }

            if (empty($request->skills)) {
                $request->merge(['skills' => []]);
            }

            $educations = [];
            foreach (['elementary', 'highschool', 'senior', 'college',] as $key) {
                $education_array = [];

                foreach ($request->{$key . "_education"} as $educ) {
                    if (isset($educ['name']) && isset($educ['location']) && isset($educ['date_graduated'])) {
                        $education_array[] = $educ;
                    }
                }

                if (!empty($education_array)) {
                    $educations[$key] = $education_array;
                }
            }
            if (!empty($educations)) {
                $request->merge(['education' => $educations]);
            }


            $resume = Resume::find($id);
            $resume->update($request->all());
            return redirect('/dashboard/' . $id)->with('success', 'Resume updated successfully.');
        } catch (\Throwable $th) {
            return $th->getMessage();
        }
    }

    public function show($id)
    {
        $user = Auth::user()->email;
        $resume = Resume::find($id);
        if (!$resume) {
            return redirect()->route('dashboard.index')->withErrors(['error' => 'Resume not found.']);
        }
        $data = array_merge(['user' => $user], $resume->toArray());
        return view('resume', $data);
    }

    public function delete($id)
    {
        $resume = Resume::find($id);

        if ($resume) {
            if ($resume->image != "default-avatar.jpg") {
                $imagePath = public_path('images') . '/' . basename($resume->image);
                if (file_exists($imagePath)) {
                    unlink($imagePath);
                }
            }
            $resume->delete();
        }

        return;
    }

    public function add_application(Request $request)
    {
        try {
            $request->validate([
                'company_name' => 'required',
                'company_image' => 'required',
                'status' => 'required',
                'resume_id' => 'required',
            ]);
            $resume = Resume::find($request->resume_id);
            $applications = $resume->applications;
            $applications[] = [
                'company_name' => $request->company_name,
                'company_image' => $request->company_image,
                'status' => $request->status,
                'date' => $request->date
            ];
            $resume->update(['applications' => $applications]);

            return redirect()->back()->with('success', 'Application added successfully.');
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            return redirect()->back()->withErrors(['error' => $th->getMessage()])->withInput();
        }
    }


    public function delete_application(Request $request)
    {
        $resume = Resume::find($request->resume_id);
        $applications = $resume->applications;
        unset($applications[$request->index]);
        $resume->update(['applications' => array_values($applications)]);

        return redirect()->back()->with('success', 'Application added successfully.');
    }
}
