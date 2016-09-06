<?php
/**
 * EYuiSearchable class file.
 *
 *	Allows a data model to give response to EYuiSearch and similar classes who need access
 *  to client data model.
 *
 *	@see
 *		EYuiSearch
 *
 * @author Christian Salazar <christiansalazarh@gmail.com>
 * @link https://bitbucket.org/christiansalazarh/eyui
 * @license http://opensource.org/licenses/bsd-license.php
 */
interface EYuiSearchable {

	/**
		perform a search in client data model.
		@param text string is the provided user-text to be searched.
		@param context string is the same value passed to widget used at your convenience.
		@returns an array of models. each model will be processed later with CHtml::listData.
		
		@see
			EYuiSearch
	*/
	public function eyuisearchable_findModels($text,$context);
}