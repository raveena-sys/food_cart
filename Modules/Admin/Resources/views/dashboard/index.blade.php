@extends('admin::layouts.app')
@section('content')
    <?php $current = 'dashboard'; ?>
    <main class="main-content admin-dashboard">
            <div class="container-fluid">
                <div class="page-title-row filter-page-btn-sm mb-2">
                    <div class="left-side">
                        <h2 class="page-title text-capitalize mb-0">
                        <?php echo $current ?>
                        </h2>
                    </div>
                </div>
                <div class="counter-wrapper pt-xl-4 pt-md-2 pt-0">
                    <div class="row custom-gutters">
                        <div class="col-sm-12 col-lg-12 mb-3 mb-lg-0">
                            <h3 class="h-20">Total</h3>
                             <div class="" id="totalCards">{!! $html !!}</div>
                            
                        </div>
                        <div class="row">&nbsp</div>
                        <div class="col-sm-12 col-lg-12">
                            <h3 class="h-20">Order Transaction</h3>
                            <div class="dashboard_chart" id="totalCards">
                                <div class="main-section">
                                    {!! $completechart->html() !!}
                                </div>
                            </div>
                        </div>
                        <div class="row">&nbsp</div>
                        <div class="col-sm-12 col-lg-12">
                            <h3 class="h-20">Order Transaction</h3>
                            <div class="dashboard_chart" id="totalCards">
                                <div class="main-section">
                                    {!! $monthchart->html() !!}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </main>

{!! Charts::scripts() !!}
{!! $completechart->script() !!}
{!! $monthchart->script() !!}

 @endsection
