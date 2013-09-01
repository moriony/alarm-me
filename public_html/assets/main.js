$(function() {
    $('[data-project]').each(function(index, element) {
        var project = $(element).data('project');

        $(element).find('[data-project-data]').each(function(index, dataElement) {
            var dataType = $(dataElement).data('projectData');

            $.ajax({
                url: '/' + dataType,
                data: {
                    project: project
                },
                dataType: 'json',
                success: function(data, status) {
                    var value = data.value;
                    console.log(value);
                    if (dataType == 'status') {
                        if (value) {
                            value = '<span class="label label-success">working</span>';
                        } else {
                            value = '<span class="label label-important">down</span>';
                        }
                    } else if (!value) {
                        value = '<span class="label label-warning">fail</span>';
                    }
                    $(dataElement).html(value);
                }
            });
        });
    });
});
