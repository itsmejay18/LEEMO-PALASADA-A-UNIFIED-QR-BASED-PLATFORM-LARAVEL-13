import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/css/coreui-dashboard.css',
                'resources/vendor/coreui-kit/css/style.css',
                'resources/vendor/coreui-kit/css/examples.css',
                'resources/vendor/coreui-kit/vendors/simplebar/css/simplebar.css',
                'resources/vendor/coreui-kit/vendors/@coreui/icons/css/free.min.css',
                'resources/vendor/coreui-kit/vendors/@coreui/chartjs/css/coreui-chartjs.css',
                'resources/vendor/coreui-kit/js/config.js',
                'resources/vendor/coreui-kit/js/color-modes.js',
                'resources/vendor/coreui-kit/vendors/@coreui/coreui/js/coreui.bundle.min.js',
                'resources/vendor/coreui-kit/vendors/simplebar/js/simplebar.min.js',
                'resources/vendor/coreui-kit/vendors/chart.js/js/chart.umd.js',
                'resources/vendor/coreui-kit/vendors/@coreui/chartjs/js/coreui-chartjs.js',
                'resources/vendor/coreui-kit/vendors/@coreui/utils/js/index.js',
                'resources/vendor/coreui-kit/vendors/@coreui/icons/svg/free.svg',
                'resources/vendor/coreui-kit/assets/brand/coreui.svg',
                'resources/vendor/coreui-kit/assets/img/avatars/8.jpg',
                'resources/img/sidebarlogobranding.png',
                'resources/img/sidebarlogo-cropped.png',
                'resources/img/landinbranding.png',
                'resources/img/favicon.png',
                'resources/js/app.js',
            ],
            refresh: true,
        }),
    ],
});
