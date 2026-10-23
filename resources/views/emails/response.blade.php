<div style="font-family:Arial,sans-serif;max-width:600px;margin:auto;color:#1e293b">
  <h2 style="color:#0e2a6b">{{ $admin ? 'New response received' : 'Thanks, we received your response' }}</h2>
  <p style="color:#64748b">{{ $form->title }}</p>

  @if ($admin)
    <p style="background:#eff6ff;padding:10px 14px;border-radius:8px">
      <b>Submitted from:</b>
      @if ($source)<a href="{{ $source }}">{{ $source }}</a>@else Direct link @endif
    </p>
  @endif

  <table style="width:100%;border-collapse:collapse;font-size:14px">
    @foreach ($rows as $r)
      @php $v = is_array($r['value'] ?? '') ? implode(', ', $r['value']) : ($r['value'] ?? ''); @endphp
      <tr>
        <td style="padding:8px 10px;border:1px solid #e2e8f0;background:#f8fafc;width:35%"><b>{{ $r['label'] }}</b></td>
        <td style="padding:8px 10px;border:1px solid #e2e8f0">{{ $v !== '' ? $v : '—' }}</td>
      </tr>
    @endforeach
  </table>
</div>