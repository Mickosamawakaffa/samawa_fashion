@extends('layouts.frontend')

@section('title', 'Upload Bukti Pembayaran - Samawa Fashion')

@section('content')
<div class="py-5">
    <div class="container">
        <div class="section-title">
            <h2>Upload Bukti Pembayaran</h2>
            <div class="divider"></div>
        </div>
        
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card mb-4">
                    <div class="card-header">
                        <i class="fas fa-credit-card me-2"></i> Informasi Pembayaran
                    </div>
                    <div class="card-body">
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>
                            <strong>Silakan transfer ke rekening berikut:</strong>
                        </div>
                        <div class="bg-light p-4 rounded mb-4">
                            <p class="mb-2"><strong>Bank BCA</strong></p>
                            <p class="mb-2"><strong>No. Rekening:</strong> 1234567890</p>
                            <p class="mb-0"><strong>a.n. Samawa Fashion</strong></p>
                        </div>
                        
                        <div class="mb-4">
                            <strong>Total yang harus dibayar:</strong>
                            <h3 class="text-gold" style="color: var(--gold-color);">
                                Rp {{ number_format($order->total_amount, 0, ',', '.') }}
                            </h3>
                        </div>
                    </div>
                </div>
                
                <div class="card">
                    <div class="card-header">
                        <i class="fas fa-upload me-2"></i> Upload Bukti Transfer
                    </div>
                    <div class="card-body">
                        <form action="{{ route('payment.store', $order->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            
                            <div class="mb-3">
                                <label class="form-label">Bukti Transfer <span class="text-danger">*</span></label>
                                <input type="file" name="proof_image" class="form-control" required accept="image/*,application/pdf">
                                <small class="text-muted">Format: JPG, PNG, atau PDF. Maksimal 2MB.</small>
                                @error('proof_image')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Catatan (Opsional)</label>
                                <textarea name="notes" class="form-control" rows="3" placeholder="Tambahkan catatan jika diperlukan">{{ old('notes') }}</textarea>
                                @error('notes')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="alert alert-warning">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                <small>Pastikan bukti transfer jelas dan dapat dibaca. Pembayaran akan diverifikasi dalam 1x24 jam.</small>
                            </div>
                            
                            <button type="submit" class="btn-gold w-100">
                                <i class="fas fa-paper-plane me-2"></i> Upload Bukti Pembayaran
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
