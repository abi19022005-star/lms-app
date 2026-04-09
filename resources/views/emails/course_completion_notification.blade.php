@component('mail::message')
# 🎓 Selamat Anda Telah Menyelesaikan Kursus!

Halo **{{ $enrollment->user->name }}**,

Kami dengan bangga memberitahukan bahwa Anda telah berhasil menyelesaikan kursus:

## {{ $enrollment->course->judul }}

Instruktur: **{{ $enrollment->course->guru->name }}**

---

### 📊 Ringkasan Pembelajaran Anda:

- **Status**: Selesai ✅
- **Progress**: {{ $enrollment->progress }}%
- **Tanggal Mulai**: {{ $enrollment->enrolled_at->format('d F Y') }}
- **Tanggal Selesai**: {{ $enrollment->completed_at->format('d F Y') }}

---

### 🎯 Langkah Selanjutnya:

@if($enrollment->course->quizzes->count() > 0 && $enrollment->course->quizzes->first()->attempts()->where('user_id', $enrollment->user_id)->doesntExist())

Untuk mendapatkan sertifikat resmi, silakan selesaikan **kuis akhir** pada kursus ini.

@elseif($enrollment->user->certificates()->where('course_id', $enrollment->course_id)->exists())

Anda sudah mendapatkan sertifikat untuk kursus ini! Silakan kunjungi dashboard untuk melihat sertifikat Anda.

@else

Selesaikan kuis akhir untuk mendapatkan sertifikat resmi yang dapat Anda bagikan.

@endif

@component('mail::button', ['url' => route('courses.show', $enrollment->course)])
Lihat Detail Kursus
@endcomponent

---

### 💡 Saran Kami:

Jangan ragu untuk mengulang materi atau mengeksplorasi kursus lainnya di platform kami. Terus belajar dan tingkatkan keterampilan Anda!

Terima kasih telah belajar bersama kami.

@component('mail::footer')
© {{ date('Y') }} Learning Management System. Semua hak dilindungi.
@endcomponent
@endcomponent
