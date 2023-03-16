<nav aria-label="breadcrumb">
<ol class="breadcrumb">
  @foreach($page_breadcrumbs as $item)
    @if($loop->last)
      @php
        $active = ' active';
      @endphp
    @endif
    <li style="font-size:18px; font-weight:bold;" class="breadcrumb-item{{ $active ?? "" }}  {{ isset($active) ? "aria-current='page'" : '' }}">
      @if(!$loop->last)
        <a href={{ $item['url'] ?? "#" }}>{{ $item['title'] ?? '' }}</a>
      @else
        {{ $item['title' ?? ''] }}
      @endif
    </li>
  @endforeach

</ol>

</nav>
{{-- <nav aria-label="breadcrumb">
    <ol class="breadcrumb mb-0">
        <li class="breadcrumb-item"><a href="../../index.html">Home</a></li>
        <li class="breadcrumb-item"><a href="../index.html">Tables</a></li>
        <li class="breadcrumb-item active" aria-current="page">Static Tables</li>
    </ol>
</nav> --}}
<!-- END : Breadcrumb -->



