(function ($) {
    $(document).ready(e => {



        // atrribute box button
        $('#addAttrBtn').click(e => {

            let attrTitle = (($(e.currentTarget).siblings('.attr-title').val()).replace(' ', '-')).replace(' ', '-')
            if (attrTitle.length < 1) {
                alert('Please Enter Your Attribute Name');
                return;
            }
            // atribute box html
            let attrHtml = '<div class="product-attr mt-2 attr-' + attrTitle + '"  data-opname="' + attrTitle + '"><div class="attr-name"><div class="row"><div class="col"><label for="">Attribute Name</label> <input type="text" name="attr-title" disabled value="' + attrTitle + '" placeholder="Display Option" class="form-control"></div><div class="col"><label> Attribute Type </label><select type="text" name="attr-type" Placeholder="Attribute Type" class="form-select"><option value="normal"> Normal </option><option value="flat"> Flat </option> <option value="percent"> Percent </option><option value="lft"> Linear Ft. </option> <option value="sqft"> Squre Ft. </option></select></div><div class="col"><label for="">Css Class</label> <input type="text" name="css-class" value="select-' + attrTitle + '" class="form-control"></div> </div></div><div class="attibute-options"><div class="row opt-row"> <div class="col"><div class="form-group"><label for="" class="form-label">Variant Title</label><input type="text" data-opname="' + attrTitle + '" placeholder="Single Sided" name="attr-name" class="variable-title form-control"> </div> </div>  <div class="col"><div class="form-group">  <label class="form-label">Variant Price</label><input  data-opname="' + attrTitle + '" type="text" placeholder="10" value="0" name="attr-price" class="variable-price form-control"> </div></div> </div>	 </div><a class="btn btn-primary button-large mt-2 addOptBtn" data-opname="' + attrTitle + '">Add Option</a> <a class="btn btn-danger button-large mt-2 removeAttr" data-opname="' + attrTitle + '">Remove Attribute</a>  </div>';
            $('.product-attr-container').append(attrHtml);

            $(e.currentTarget).siblings('.attr-title').val('')
        })

        $('.removeAttr').click(e => {
            let attrName = $(e.currentTarget).attr('data-opname');
            $(e.currentTarget).parent('.product-attr').remove();
            // $(e.currentTarget).parent('.product-attr').empty();
            // $(e.currentTarget).parent('.product-attr').removeAttr('class');
        })




        // add option button click
        $(document).on('click', '.product-attr-container .addOptBtn', e => {

            let opname = $(e.currentTarget).attr('data-opname').toLowerCase();
            let optionHtml = '<div class="row opt-row"><div class="col"> <div class="form-group"> <label class="form-label">Variant Title</label><input type="text" placeholder="Single Sided" name="attr-name" data-opname="' + opname + '" class="variable-title form-control"> </div> </div>  <div class="col"> <div class="form-group"> <label class="form-label">Variant Price</label> <input value="0" data-opname="' + opname + '" type="text" placeholder="10" name="attr-price" class="variable-price form-control"></div></div></div>';
            $(e.currentTarget).siblings('.attibute-options').append(optionHtml);
        })

        $('.product-attr-container input').change(e => {
            $('.saveOptBtn').text('Save Options')
        })



        // save button click action
        $('.saveOptBtn').click(e => {

            let allAttrData = [];
            let totalAttributes = parseInt($('.product-attr-container .product-attr').length)
            for (let i = 0; i < totalAttributes; i++) {
                let attrName = $('.product-attr-container .product-attr:nth-child(' + (i + 1) + ')').attr('data-opname');
                let attrType = $('.product-attr-container .product-attr:nth-child(' + (i + 1) + ') select[name="attr-type"]').val();
                let attrCssClass = $('.product-attr-container .product-attr:nth-child(' + (i + 1) + ') input[name="css-class"]').val();
                let totalOptions = parseInt($('.product-attr-container .product-attr:nth-child(' + (i + 1) + ') .row.opt-row').length);
                let singAttr = {
                    name: attrName,
                    type: attrType,
                    cssClass: attrCssClass,
                    options: [],
                }
                for (let opNumer = 0; opNumer < (totalOptions); opNumer++) {

                    let optionName = $('.attr-' + attrName + ' .row.opt-row:nth-child(' + (opNumer + 1) + ') input[name=attr-name]').val()
                    //alert('.attr-'+attrName+' .row.opt-row:nth-child('+(opNumer+1)+') input[name=attr-name]');
                    let optionPrice = $('.attr-' + attrName + ' .row.opt-row:nth-child(' + (opNumer + 1) + ') input[name=attr-price]').val()
                    if (optionName.length < 1) {
                        continue;
                    }

                    singAttr.options.push({ [optionName]: optionPrice });

                }
                allAttrData.push(singAttr);
            }
            console.log(allAttrData);
            $('#productAttrJson').val(JSON.stringify(allAttrData));
            $(e.target).text('Saved')
        })

        $('#publish').click(e => {

            $('.saveOptBtn').trigger('click')
        })


    })
})(jQuery)