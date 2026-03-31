<?php

namespace App\Http\Controllers\Candidate;

use App\Http\Controllers\Controller;
use App\Models\CandidateEducation;
use App\Models\CandidateExperience;
use App\Models\CandidateSkill;
use App\Models\Skill;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function edit()
    {
        $candidate = auth()->user()->candidateProfile;
        $candidate->load('skills.skill', 'experiences', 'educations');
        $skills = Skill::orderBy('category')->orderBy('name')->get()->groupBy('category');

        return view('candidate.profile.edit', compact('candidate', 'skills'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'headline'      => ['nullable', 'string', 'max:255'],
            'bio'           => ['nullable', 'string', 'max:2000'],
            'location'      => ['nullable', 'string', 'max:255'],
            'linkedin_url'  => ['nullable', 'url'],
            'github_url'    => ['nullable', 'url'],
            'portfolio_url' => ['nullable', 'url'],
            'visibility'    => ['required', 'in:public,private,employers'],
            'is_open_to_work' => ['boolean'],
        ]);

        $candidate = auth()->user()->candidateProfile;

        $candidate->update([
            'headline'        => $request->headline,
            'bio'             => $request->bio,
            'location'        => $request->location,
            'linkedin_url'    => $request->linkedin_url,
            'github_url'      => $request->github_url,
            'portfolio_url'   => $request->portfolio_url,
            'visibility'      => $request->visibility,
            'is_open_to_work' => $request->boolean('is_open_to_work'),
        ]);

        $this->updateProfileCompletion($candidate);

        return back()->with('success', 'Profile updated successfully.');
    }

    public function updateSkills(Request $request)
    {
        $request->validate([
            'skills'   => ['nullable', 'array'],
            'skills.*' => ['exists:skills,id'],
        ]);

        $candidate = auth()->user()->candidateProfile;
        $candidate->skills()->delete();

        if ($request->filled('skills')) {
            foreach ($request->skills as $skillId) {
                CandidateSkill::create([
                    'candidate_id' => $candidate->id,
                    'skill_id'     => $skillId,
                ]);
            }
        }

        return back()->with('success', 'Skills updated successfully.');
    }

    public function storeExperience(Request $request)
    {
        $request->validate([
            'company'     => ['required', 'string', 'max:255'],
            'role'        => ['required', 'string', 'max:255'],
            'location'    => ['nullable', 'string', 'max:255'],
            'start_date'  => ['required', 'date'],
            'end_date'    => ['nullable', 'date', 'after:start_date'],
            'is_current'  => ['boolean'],
            'description' => ['nullable', 'string', 'max:1000'],
        ]);

        $candidate = auth()->user()->candidateProfile;

        CandidateExperience::create([
            'candidate_id' => $candidate->id,
            'company'      => $request->company,
            'role'         => $request->role,
            'location'     => $request->location,
            'start_date'   => $request->start_date,
            'end_date'     => $request->boolean('is_current') ? null : $request->end_date,
            'is_current'   => $request->boolean('is_current'),
            'description'  => $request->description,
        ]);

        return back()->with('success', 'Experience added.');
    }

    public function destroyExperience(CandidateExperience $experience)
    {
        abort_if($experience->candidate_id !== auth()->user()->candidateProfile->id, 403);
        $experience->delete();
        return back()->with('success', 'Experience removed.');
    }

    public function storeEducation(Request $request)
    {
        $request->validate([
            'institution' => ['required', 'string', 'max:255'],
            'degree'      => ['required', 'string', 'max:255'],
            'field'       => ['nullable', 'string', 'max:255'],
            'start_year'  => ['required', 'integer', 'min:1950', 'max:' . date('Y')],
            'end_year'    => ['nullable', 'integer', 'min:1950', 'max:' . (date('Y') + 6)],
            'is_current'  => ['boolean'],
        ]);

        $candidate = auth()->user()->candidateProfile;

        CandidateEducation::create([
            'candidate_id' => $candidate->id,
            'institution'  => $request->institution,
            'degree'       => $request->degree,
            'field'        => $request->field,
            'start_year'   => $request->start_year,
            'end_year'     => $request->boolean('is_current') ? null : $request->end_year,
            'is_current'   => $request->boolean('is_current'),
        ]);

        return back()->with('success', 'Education added.');
    }

    public function destroyEducation(CandidateEducation $education)
    {
        abort_if($education->candidate_id !== auth()->user()->candidateProfile->id, 403);
        $education->delete();
        return back()->with('success', 'Education removed.');
    }

    public function uploadCv(Request $request)
    {
        $request->validate([
            'cv' => ['required', 'file', 'mimes:pdf,doc,docx', 'max:5120'],
        ]);

        $candidate = auth()->user()->candidateProfile;
        $candidate->addMediaFromRequest('cv')->toMediaCollection('cv');

        return back()->with('success', 'CV uploaded successfully.');
    }

    private function updateProfileCompletion($candidate): void
    {
        $score = 0;
        if ($candidate->headline)      $score += 20;
        if ($candidate->bio)           $score += 20;
        if ($candidate->location)      $score += 10;
        if ($candidate->skills()->count() > 0) $score += 20;
        if ($candidate->experiences()->count() > 0) $score += 15;
        if ($candidate->getMedia('cv')->count() > 0) $score += 15;

        $candidate->update(['profile_completion' => $score]);
    }
}