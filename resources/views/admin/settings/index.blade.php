@extends('layouts.app')

@section('title', 'Pengaturan Sistem')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div>
        <h1 class="text-2xl font-bold text-gray-800 font-poppins">
            <i class="fas fa-cog mr-3 text-blue-600"></i>Pengaturan Sistem
        </h1>
        <p class="text-sm text-gray-500 mt-1">Kelola konfigurasi aplikasi e-learning</p>
    </div>

    <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf

        <div class="grid gap-6 lg:grid-cols-2">
            <!-- General Settings -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="bg-gradient-to-r from-blue-600 to-blue-500 px-5 py-4">
                    <h6 class="text-sm font-semibold text-white"><i class="fas fa-globe mr-2"></i> Pengaturan Umum</h6>
                </div>
                <div class="p-5 space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nama Aplikasi</label>
                        <input type="text" name="app_name" value="{{ old('app_name', $settings['app_name']) }}"
                               class="w-full px-3 py-2 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi Aplikasi</label>
                        <textarea name="app_description" rows="3"
                                  class="w-full px-3 py-2 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('app_description', $settings['app_description']) }}</textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Logo Aplikasi</label>
                        @if($settings['app_logo'])
                        <div class="mb-2">
                            <img src="{{ Storage::url($settings['app_logo']) }}" alt="Logo" class="h-16 object-contain">
                        </div>
                        @endif
                        <input type="file" name="app_logo" accept="image/*"
                               class="w-full px-3 py-2 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <p class="text-xs text-gray-400 mt-1">Upload logo baru (max 2MB)</p>
                    </div>
                    <div class="flex items-center justify-between">
                        <label class="text-sm font-medium text-gray-700">Aktifkan Registrasi User Baru</label>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="enable_registration" value="1" class="sr-only peer" {{ $settings['enable_registration'] == 1 || $settings['enable_registration'] == true ? 'checked' : '' }}>
                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                        </label>
                    </div>
                    <div class="flex items-center justify-between">
                        <label class="text-sm font-medium text-gray-700">Mode Maintenance</label>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="maintenance_mode" value="1" class="sr-only peer" id="maintenance_mode" {{ $settings['maintenance_mode'] == 1 || $settings['maintenance_mode'] == true ? 'checked' : '' }}>
                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                        </label>
                    </div>
                    <div id="maintenance_message_div" class="{{ $settings['maintenance_mode'] ? '' : 'hidden' }}">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Pesan Maintenance</label>
                        <textarea name="maintenance_message" rows="2"
                                  class="w-full px-3 py-2 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500">{{ $settings['maintenance_message'] }}</textarea>
                    </div>
                </div>
            </div>

            <!-- Contact & Social Media -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="bg-gradient-to-r from-cyan-600 to-cyan-500 px-5 py-4">
                    <h6 class="text-sm font-semibold text-white"><i class="fas fa-address-card mr-2"></i> Kontak & Sosial Media</h6>
                </div>
                <div class="p-5 space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Email Kontak</label>
                        <input type="email" name="contact_email" value="{{ $settings['contact_email'] }}"
                               class="w-full px-3 py-2 border border-gray-200 rounded-xl">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nomor Telepon</label>
                        <input type="text" name="contact_phone" value="{{ $settings['contact_phone'] }}"
                               class="w-full px-3 py-2 border border-gray-200 rounded-xl">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Alamat</label>
                        <textarea name="contact_address" rows="2"
                                  class="w-full px-3 py-2 border border-gray-200 rounded-xl">{{ $settings['contact_address'] }}</textarea>
                    </div>
                    <hr class="border-gray-100">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1"><i class="fab fa-facebook text-blue-600 mr-2"></i> Facebook</label>
                        <input type="url" name="social_facebook" value="{{ $settings['social_facebook'] }}" placeholder="https://facebook.com/username"
                               class="w-full px-3 py-2 border border-gray-200 rounded-xl">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1"><i class="fab fa-twitter text-cyan-500 mr-2"></i> Twitter</label>
                        <input type="url" name="social_twitter" value="{{ $settings['social_twitter'] }}"
                               class="w-full px-3 py-2 border border-gray-200 rounded-xl">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1"><i class="fab fa-instagram text-pink-600 mr-2"></i> Instagram</label>
                        <input type="url" name="social_instagram" value="{{ $settings['social_instagram'] }}"
                               class="w-full px-3 py-2 border border-gray-200 rounded-xl">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1"><i class="fab fa-linkedin text-blue-700 mr-2"></i> LinkedIn</label>
                        <input type="url" name="social_linkedin" value="{{ $settings['social_linkedin'] }}"
                               class="w-full px-3 py-2 border border-gray-200 rounded-xl">
                    </div>
                </div>
            </div>

            <!-- Email Settings -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="bg-gradient-to-r from-emerald-600 to-emerald-500 px-5 py-4">
                    <h6 class="text-sm font-semibold text-white"><i class="fas fa-envelope mr-2"></i> Pengaturan Email</h6>
                </div>
                <div class="p-5 space-y-4">
                    <div class="grid gap-4 sm:grid-cols-2">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Mail Driver</label>
                            <select name="mail_driver" class="w-full px-3 py-2 border border-gray-200 rounded-xl">
                                <option value="smtp" {{ $settings['mail_driver'] == 'smtp' ? 'selected' : '' }}>SMTP</option>
                                <option value="sendmail" {{ $settings['mail_driver'] == 'sendmail' ? 'selected' : '' }}>Sendmail</option>
                                <option value="mailgun" {{ $settings['mail_driver'] == 'mailgun' ? 'selected' : '' }}>Mailgun</option>
                                <option value="ses" {{ $settings['mail_driver'] == 'ses' ? 'selected' : '' }}>SES</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Mail Host</label>
                            <input type="text" name="mail_host" value="{{ $settings['mail_host'] }}" class="w-full px-3 py-2 border border-gray-200 rounded-xl">
                        </div>
                    </div>
                    <div class="grid gap-4 sm:grid-cols-2">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Mail Port</label>
                            <input type="number" name="mail_port" value="{{ $settings['mail_port'] }}" class="w-full px-3 py-2 border border-gray-200 rounded-xl">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Encryption</label>
                            <select name="mail_encryption" class="w-full px-3 py-2 border border-gray-200 rounded-xl">
                                <option value="tls" {{ $settings['mail_encryption'] == 'tls' ? 'selected' : '' }}>TLS</option>
                                <option value="ssl" {{ $settings['mail_encryption'] == 'ssl' ? 'selected' : '' }}>SSL</option>
                                <option value="" {{ !$settings['mail_encryption'] ? 'selected' : '' }}>None</option>
                            </select>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Username</label>
                        <input type="text" name="mail_username" value="{{ $settings['mail_username'] }}" class="w-full px-3 py-2 border border-gray-200 rounded-xl">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                        <input type="password" name="mail_password" autocomplete="off" class="w-full px-3 py-2 border border-gray-200 rounded-xl" placeholder="Kosongkan jika tidak ingin mengubah">
                    </div>
                    <div class="grid gap-4 sm:grid-cols-2">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">From Address</label>
                            <input type="email" name="mail_from_address" value="{{ $settings['mail_from_address'] }}" class="w-full px-3 py-2 border border-gray-200 rounded-xl">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">From Name</label>
                            <input type="text" name="mail_from_name" value="{{ $settings['mail_from_name'] }}" class="w-full px-3 py-2 border border-gray-200 rounded-xl">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Payment & Course Settings -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="bg-gradient-to-r from-amber-600 to-amber-500 px-5 py-4">
                    <h6 class="text-sm font-semibold text-white"><i class="fas fa-money-bill mr-2"></i> Pembayaran & Kursus</h6>
                </div>
                <div class="p-5 space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Metode Pembayaran</label>
                        <select name="payment_method" class="w-full px-3 py-2 border border-gray-200 rounded-xl">
                            <option value="manual" {{ $settings['payment_method'] == 'manual' ? 'selected' : '' }}>Manual (Transfer Bank)</option>
                            <option value="midtrans" {{ $settings['payment_method'] == 'midtrans' ? 'selected' : '' }}>Midtrans</option>
                            <option value="xendit" {{ $settings['payment_method'] == 'xendit' ? 'selected' : '' }}>Xendit</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Mata Uang</label>
                        <select name="currency" class="w-full px-3 py-2 border border-gray-200 rounded-xl">
                            <option value="IDR" {{ $settings['currency'] == 'IDR' ? 'selected' : '' }}>Indonesian Rupiah (IDR)</option>
                            <option value="USD" {{ $settings['currency'] == 'USD' ? 'selected' : '' }}>US Dollar (USD)</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Pajak (%)</label>
                        <input type="number" name="tax_rate" value="{{ $settings['tax_rate'] }}" min="0" max="100" class="w-full px-3 py-2 border border-gray-200 rounded-xl">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nilai Kelulusan Default (%)</label>
                        <input type="number" name="default_passing_score" value="{{ $settings['default_passing_score'] }}" min="0" max="100" class="w-full px-3 py-2 border border-gray-200 rounded-xl">
                        <p class="text-xs text-gray-400 mt-1">Nilai minimal untuk lulus kuis</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Submit Buttons -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
            <div class="flex justify-end gap-3">
                <a href="{{ route('admin.dashboard') }}" class="px-4 py-2 text-sm text-gray-600 bg-gray-100 rounded-xl hover:bg-gray-200 transition-colors">
                    <i class="fas fa-times mr-2"></i> Batal
                </a>
                <button type="submit" class="px-4 py-2 text-sm text-white bg-blue-600 rounded-xl hover:bg-blue-700 transition-all shadow-sm">
                    <i class="fas fa-save mr-2"></i> Simpan Pengaturan
                </button>
            </div>
        </div>
    </form>
</div>

@push('scripts')
<script>
    document.getElementById('maintenance_mode').addEventListener('change', function() {
        const div = document.getElementById('maintenance_message_div');
        if (this.checked) {
            div.classList.remove('hidden');
        } else {
            div.classList.add('hidden');
        }
    });
</script>
@endpush
@endsection
