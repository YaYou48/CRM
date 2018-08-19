$(function(){
    var customerInput = $("#myCustomerInput");
    var customerName = $(".customerName");
    var customerCard = $(".customerCard");
    customerInput.keyup(function(){
        $(".collapse").removeClass("show");
        var filter = customerInput.val().toUpperCase();
        for (i = 0; i < customerName.length; i++) {
            if (filter) {
                if (customerName[i].innerText.toUpperCase().indexOf(filter) >= 0) {
                    customerCard[i].style.display = "";
                }
                else {
                    customerCard[i].style.display = "none";
                }
            }
            else{
                customerCard[i].style.display = "";
            }
        }
    });
    var businessInput = $("#myBusinessInput");
    var businessName = $(".businessName");
    var businessCard = $(".businessCard");
    businessInput.keyup(function(){
        $(".collapse").removeClass("show");
        var filter = businessInput.val().toUpperCase();
        for (i = 0; i < businessName.length; i++) {
            if (filter) {
                if (businessName[i].innerText.toUpperCase().indexOf(filter) >= 0) {
                    businessCard[i].style.display = "";
                }
                else {
                    businessCard[i].style.display = "none";
                }
            }
            else{
                businessCard[i].style.display = "";
            }
        }
    });
    var customerInputCancel = $("#customerInputCancel");
    customerInputCancel.click(function(){
        customerInput.val("");
        for (i = 0; i < customerName.length; i++) {
            customerCard[i].style.display = "";
            $(".collapse").removeClass("show");
        }
    });
    var businessInputCancel = $("#businessInputCancel");
    businessInputCancel.click(function(){
        businessInput.val("");
        for (i = 0; i < businessName.length; i++) {
            businessCard[i].style.display = "";
            $(".collapse").removeClass("show");
        }
    });
    $(".toBusinessCard").click(function(){
        $(".collapse").removeClass("show");
        $("#list-customer").removeClass("active show");
        $("#list-customer-list").removeClass("active show");
        $("#list-business-list").addClass("active show");
        $("#list-business").addClass("active show");
        var url = this.href;
        var getIndex = url.indexOf("#");
        var anchor = url.substring(getIndex);
        $(anchor).addClass("show");
    });
    $(".toCustomerCard").click(function(){
        $(".collapse").removeClass("show");
        $("#list-business").removeClass("active show");
        $("#list-business-list").removeClass("active show");
        $("#list-customer-list").addClass("active show");
        $("#list-customer").addClass("active show");
        var url = this.href;
        var getIndex = url.indexOf("#");
        var anchor = url.substring(getIndex);
        $(anchor).addClass("show");
    });
});
