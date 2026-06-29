<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Testimonial;
use Illuminate\Http\Request;

class TestimonialController extends Controller
{
    public function index()
    {
        $testimonials = Testimonial::with('user')->orderByDesc('created_at')->paginate(10);
        return view('admin.testimonials.index', compact('testimonials'));
    }

    public function approve($id)
    {
        $testimonial = Testimonial::findOrFail($id);
        $testimonial->update([
            'is_approved' => true,
        ]);

        return redirect()->route('admin.testimonials.index')->with('success', 'Testimoni berhasil disetujui');
    }

    public function destroy($id)
    {
        $testimonial = Testimonial::findOrFail($id);
        $testimonial->delete();

        return redirect()->route('admin.testimonials.index')->with('success', 'Testimoni berhasil dihapus');
    }
}
