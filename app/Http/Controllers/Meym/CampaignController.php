<?php

namespace App\Http\Controllers\Meym;

use App\Http\Controllers\Controller;
use App\Models\Campaign;
use App\Models\CampaignUpdate;
use App\Models\WhatsappThread;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CampaignController extends Controller
{
    public function index(Request $request)
    {
        $query = Campaign::with('creator');

        // Apply filters
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('page_name', 'like', '%' . $request->search . '%')
                  ->orWhere('owner_name', 'like', '%' . $request->search . '%')
                  ->orWhere('specialization', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('specialization')) {
            $query->where('specialization', $request->specialization);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('date_from')) {
            $query->where('start_date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->where('end_date', '<=', $request->date_to);
        }

        $campaigns = $query->latest()->paginate(15);
        $specializations = Campaign::distinct()->pluck('specialization');

        return view('meym.campaigns.index', compact('campaigns', 'specializations'));
    }

    public function create()
    {
        return view('meym.campaigns.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'page_name' => 'required|string|max:255',
            'owner_name' => 'required|string|max:255',
            'specialization' => 'required|string|max:255',
            'budget_total' => 'required|numeric|min:0',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'launch_date' => 'required|date',
            'stop_date' => 'nullable|date',
            'notes' => 'nullable|string',
        ]);

        $validated['created_by'] = Auth::id();

        Campaign::create($validated);

        return redirect()->route('meym.campaigns.index')
            ->with('success', 'تم إنشاء الحملة بنجاح');
    }

    public function show(Campaign $campaign)
    {
        $campaign->load(['creator', 'updates', 'whatsappThreads']);
        
        return view('meym.campaigns.show', compact('campaign'));
    }

    public function edit(Campaign $campaign)
    {
        return view('meym.campaigns.edit', compact('campaign'));
    }

    public function update(Request $request, Campaign $campaign)
    {
        $validated = $request->validate([
            'page_name' => 'required|string|max:255',
            'owner_name' => 'required|string|max:255',
            'specialization' => 'required|string|max:255',
            'budget_total' => 'required|numeric|min:0',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'launch_date' => 'required|date',
            'stop_date' => 'nullable|date',
            'notes' => 'nullable|string',
            'status' => 'required|in:active,paused,completed,cancelled',
        ]);

        $campaign->update($validated);

        return redirect()->route('meym.campaigns.show', $campaign)
            ->with('success', 'تم تحديث الحملة بنجاح');
    }

    public function destroy(Campaign $campaign)
    {
        $campaign->delete();

        return redirect()->route('meym.campaigns.index')
            ->with('success', 'تم حذف الحملة بنجاح');
    }

    public function addUpdate(Request $request, Campaign $campaign)
    {
        $validated = $request->validate([
            'update_text' => 'required|string',
        ]);

        $campaign->updates()->create([
            'update_text' => $validated['update_text'],
            'update_date' => now(),
        ]);

        return back()->with('success', 'تم إضافة التحديث بنجاح');
    }

    public function addWhatsappMessage(Request $request, Campaign $campaign)
    {
        $validated = $request->validate([
            'customer_whatsapp' => 'required|string',
            'message_content' => 'required|string',
            'message_type' => 'required|in:sent,received',
        ]);

        $campaign->whatsappThreads()->create([
            'customer_whatsapp' => $validated['customer_whatsapp'],
            'message_content' => $validated['message_content'],
            'message_type' => $validated['message_type'],
            'message_date' => now(),
        ]);

        return back()->with('success', 'تم إضافة الرسالة بنجاح');
    }

    public function report(Campaign $campaign)
    {
        $campaign->load(['updates', 'whatsappThreads']);
        
        return view('meym.campaigns.report', compact('campaign'));
    }
}
