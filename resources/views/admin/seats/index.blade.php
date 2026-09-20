@extends('layouts.app')
@section('content')
<div class="container py-4"><h1>مدیریت صندلی‌های {{ $venue->name }}</h1>
@if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
<table class="table"><thead><tr><th>ردیف</th><th>شماره</th><th>نوع</th><th>عملیات</th></tr></thead><tbody>
@foreach($seats as $seat)<tr><form method="post" action="{{ route('admin.seats.update',$seat) }}">@csrf @method('put')<td><input name="row_label" value="{{ $seat->row_label }}" class="form-control"></td><td><input name="number" type="number" value="{{ $seat->number }}" class="form-control"></td><td><select name="type" class="form-select"><option value="standard" @selected($seat->type==='standard')>استاندارد</option><option value="vip" @selected($seat->type==='vip')>VIP</option><option value="accessible" @selected($seat->type==='accessible')>دسترس‌پذیر</option></select></td><td><button class="btn btn-primary">ذخیره</button></form><form method="post" action="{{ route('admin.seats.destroy',$seat) }}" class="d-inline">@csrf @method('delete')<button class="btn btn-danger" onclick="return confirm('حذف شود؟')">حذف</button></form></td></tr>@endforeach
</tbody></table>{{ $seats->links() }}</div>
@endsection
