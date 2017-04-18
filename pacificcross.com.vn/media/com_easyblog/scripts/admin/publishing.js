EasyBlog.module('admin/publishing' , function($) {

	var module = this;

	EasyBlog.Controller(
		'Grid.Publishing',
		{
			defaultOptions : 
			{
			}
		},
		function( self )
		{
			return {

				init : function()
				{
				},

				"{self} click": function( el )
				{
					var row 	= self.element.parents( 'tr' ),
						task 	= self.element.data( 'task' );

					self.parent.selectRow(row);

					self.parent.setTask( task );

					self.parent.submitForm();
				}
			}
		}
	);
		
	module.resolve();

});