<?php

namespace App\Http\Controllers;

use App\Models\Signalement;
use Illuminate\Http\Request;

class SignalementController extends Controller
{
    

    public function index(Request $request)
    {
        $user = auth()->user();
        $query = Signalement::with(['user', 'interventions.technicien']);

        if ($user && $user->hasRole('demandeur')) {
            $query->where('user_id', $user->id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        if ($request->filled('severity')) {
            $query->where('severity', $request->severity);
        }

        $signalements = $query->latest()->paginate(10)->withQueryString();

        return view('signalements.index', compact('signalements'));
    }

    public function create()
    {
        return view('signalements.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'category' => 'required|in:plomberie,electricite,mobilier,autre',
            'severity' => 'required|in:faible,moyen,critique', // 1. اختيار الخطورة يدوياً من قِبلك
            'description' => 'required|string|min:10',
        ]);

        // 2. استقبال معيار استعجال الحصة (EST FBS)
        $occupancy = $request->input('occupancy', 'today');

        // 3. تحليل الذكاء الاصطناعي لحساب السكور والتشخيص
        $aiResult = $this->aiTriage->analyze(
            $validated['title'],
            $validated['description'],
            $validated['location'],
            $validated['category'],
            $occupancy
        );

        // 4. حفظ البلاغ بالخطورة التي اخترتها أنت
        $signalement = Signalement::create([
            'user_id' => auth()->id(),
            'title' => $validated['title'],
            'location' => $validated['location'],
            'category' => $validated['category'],
            'severity' => $validated['severity'], // الخطورة التي حددتها أنت بنفسك
            'description' => $validated['description'],
            'status' => 'signale',
            'ai_score' => $aiResult['score'],
            'ai_diagnostic' => $aiResult['diagnostic'],
            'ai_recommended_action' => $aiResult['recommended_action'],
            'ai_estimated_hours' => $aiResult['estimated_hours'],
        ]);

        // Code l-jadid (بلا message)
           return redirect()->route('signalements.index');
    }

    public function show($id)
    {
        $signalement = Signalement::with(['user', 'interventions.technicien'])->findOrFail($id);

        $user = auth()->user();
        if ($user && $user->hasRole('demandeur') && $signalement->user_id !== $user->id) {
            abort(403, 'Accès non autorisé à ce signalement.');
        }

        // 5. ميزة « Marquer comme lu » التلقائية عند معاينة التقني أو الأدمن للبلاغ
        if ($user && $user->hasRole(['technicien', 'admin'])) {
            $read = session('read_notifications', []);
            if (!in_array((int) $id, $read)) {
                $read[] = (int) $id;
                session(['read_notifications' => $read]);
            }
        }

        return view('signalements.show', compact('signalement'));
    }

    public function retriage($id)
    {
        $signalement = Signalement::findOrFail($id);

        $aiResult = $this->aiTriage->analyze(
            $signalement->title,
            $signalement->description,
            $signalement->location,
            $signalement->category,
            'today'
        );

        $signalement->update([
            'ai_score' => $aiResult['score'],
            'ai_diagnostic' => $aiResult['diagnostic'],
            'ai_recommended_action' => $aiResult['recommended_action'],
            'ai_estimated_hours' => $aiResult['estimated_hours'],
        ]);

        return back()->with('info', "Diagnostic IA réévalué avec succès (Nouveau score : {$aiResult['score']}/100).");
    }
}