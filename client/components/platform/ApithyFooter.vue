<template>
    <div class="apithy-footer">
        <div class="row align-items-center justify-content-between items">
            <div class="col-12 col-md-4 item">
                <div class="row justify-content-md-start justify-content-center">
                    <div class="col-auto">
                        <a href="mailto:info@apithy.com"><span>Contáctanos</span></a>
                    </div>
                    <div class="col-auto">
                        <a href="/terminos-y-condiciones"><span>Términos y condiciones</span></a>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-4 item">
                <div class="d-flex justify-content-center">
                    <div class="">
                        <div class="apithy-icon"></div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-4 item">
                <div class="row justify-content-md-end justify-content-center">
                    <div class="col-auto social">
                        <a href="https://www.linkedin.com/company/apithylearning/" target="_blank"><span title="Linkedin"><i class="fab fa-linkedin-in"></i></span></a>
                    </div>
                    <div class="col-auto social">
                        <a href="https://www.instagram.com/apithylearning/" target="_blank"><span title="Instagram"><i class="fab fa-instagram"></i></span></a>
                    </div>
                    <div class="col-auto social">
                        <a href="https://twitter.com/apithylearning/ " target="_blank"><span title="Twitter"><i class="fab fa-twitter"></i></span></a>
                    </div>
                    <div class="col-auto social">
                        <a href="https://www.facebook.com/apithylearning/ " target="_blank"><span title="Facebook"><i class="fab fa-facebook-square"></i></span></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "ApithyFooter"
    }
</script>

<style scoped>
    a {
        text-decoration: unset;
        color: unset;
    }
    .apithy-icon {
        width: 40px;
        height: 40px;
        background-color: #7D7D7D;
        -webkit-mask: url(https://s3-us-west-2.amazonaws.com/cdn.apithy.com/logo/new_apithy_icon_gray.png) no-repeat center;
        mask: url(https://s3-us-west-2.amazonaws.com/cdn.apithy.com/logo/new_apithy_icon_gray.png) no-repeat center;
    }
    .apithy-footer {
        height: 100px;
        background: #F5F5F5;
    }
    .apithy-footer .items {
        height: 100%;
        margin-left: 50px;
        margin-right: 50px;
    }
    .apithy-footer .item {
        font-style: normal;
        font-weight: 600;
        font-size: 15px;
        line-height: 18px;
        color: #7D7D7D;
    }
    .item .social {
        font-size: 24px;
    }
    @media only screen and (max-width: 850px) {
        .apithy-footer {
            height: auto;
            padding: 16px 0px;
        }
        .apithy-footer .item {
            padding: 16px 0px;
        }
    }
</style>
