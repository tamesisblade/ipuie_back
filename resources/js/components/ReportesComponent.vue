<template>
<div class="page-wrapper">
    <div class="container-fluid">
        <div class="row page-titles">
            <div class="col-md-5 col-8 align-self-center form-group">
                <h3 class="text-themecolor"><i class="fas fa-cog"></i> Plataforma Prolipa</h3>
            </div>
        </div>
        <div class="card card-body">
            <div>
                <apexchart width="380" type="line" :options="options" :series="series"></apexchart>
            </div>
        </div>
    </div>
    <footer class="footer">Prolipa © 2019</footer>
</div>
</template>

<script>
export default {
    data: function () {
        return {
            options: {},
            series: [44, 55, 41, 17, 15]
        };
    },

}
</script>
