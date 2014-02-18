
{*
address:      Publisher's address (usually just the city, but can be the full address for lesser-known publishers)
annote:       An annotation for annotated bibliography styles (not typical)
author:       The name(s) of the author(s) (in the case of more than one author, separated by and)
booktitle:    The title of the book, if only part of it is being cited
chapter:      The chapter number
crossref:     The key of the cross-referenced entry
edition:      The edition of a book, long form (such as "First" or "Second")
editor:       The name(s) of the editor(s)
eprint:       A specification of an electronic publication, often a preprint or a technical report
howpublished: How it was published, if the publishing method is nonstandard
institution:  The institution that was involved in the publishing, but not necessarily the publisher
journal:      The journal or magazine the work was published in
key:          A hidden field used for specifying or overriding the alphabetical order of entries (when the "author" and "editor" fields are missing). Note that this is very different from the key (mentioned just after this list) that is used to cite or cross-reference the entry.
month:        The month of publication (or, if unpublished, the month of creation)
note:         Miscellaneous extra information
number:       The "(issue) number" of a journal, magazine, or tech-report, if applicable. (Most publications have a "volume", but no "number" field.)
organization: The conference sponsor
pages:        Page numbers, separated either by commas or double-hyphens.
publisher:    The publisher's name
school:       The school where the thesis was written
series:       The series of books the book was published in (e.g. "The Hardy Boys" or "Lecture Notes in Computer Science")
title:        The title of the work
type:         The field overriding the default type of publication (e.g. "Research Note" for techreport, "{PhD} dissertation" for phdthesis, "Section" for inbook/incollection)
url:          The WWW address
volume:       The volume of a journal or multi-volume book
year:         The year of publication (or, if unpublished, the year of creation)
*}

{*---------------------------------------------------------------------------*}

<form class="TTWForm" method="post" enctype="multipart/form-data" novalidate
		action='{gl url="admin/publication/create"}'>
	<div id="publication-container" class="field f_100">
		<label for="publication">
			Find publication
		</label>
		<input class="find" autocomplete=off type="text" name="publication" placeholder="search"/>
	</div>
</form>

{*---------------------------------------------------------------------------*}

{include "templates/snippets/section_title.tpl" title={t s='Add publication' m=0}}

<div class="TTWForm-container">

	<form class="TTWForm" method="post" enctype="multipart/form-data" novalidate
		action='{gl url="admin/publication/create"}'
	>

		<div id="publication_title-container" class="field f_100">
			<label for="publication_title">
				Title
			</label>
			<input type="text" name="publication_title" id="publication_title" required>
		</div>

		<div id="publication_type-container" class="field f_100">
			<label for="publication_type">
				Type
			</label>
				<select name="publication_type" id="publication_type" required>
				<option id="publication_type-1"   value="article"       >{t s='article'       m=0}</option>
				<option id="publication_type-2"   value="book"          >{t s='book'          m=0}</option>
				<option id="publication_type-3"   value="booklet"       >{t s='booklet'       m=0}</option>
				<option id="publication_type-4"   value="conference"    >{t s='conference'    m=0}</option>
				<option id="publication_type-5"   value="inbook"        >{t s='inbook'        m=0}</option>
				<option id="publication_type-6"   value="incollection"  >{t s='incollection'  m=0}</option>
				<option id="publication_type-7"   value="inproceedings" >{t s='inproceedings' m=0}</option>
				<option id="publication_type-8"   value="manual"        >{t s='manual'        m=0}</option>
				<option id="publication_type-9"   value="mastersthesis" >{t s='mastersthesis' m=0}</option>
				<option id="publication_type-10"  value="misc"          >{t s='misc'          m=0}</option>
				<option id="publication_type-11"  value="phdthesis"     >{t s='phdthesis'     m=0}</option>
				<option id="publication_type-12"  value="proceedings"   >{t s='proceedings'   m=0}</option>
				<option id="publication_type-13"  value="techreport"    >{t s='techreport'    m=0}</option>
				<option id="publication_type-14"  value="unpublished"   >{t s='unpublished'   m=0}</option>
			</select>
		</div>

		<div id="publication_year-container" class="field f_100">
			<label for="publication_year">
				Year
			</label>
			<select name="publication_year" id="publication_year" class="yearpicker" required>
			</select>
		</div>

		<div id="publication_month-container" class="field f_100">
			<label for="publication_month">
				Month
			</label>
			<select name="publication_month" id="publication_month" class="monthpicker">
			</select>
		</div>

		<div id="publication_toappear-container" class="field f_100">
			<label for="publication_toappear">
				To appear
			</label>
			<input type="checkbox" name="publication_toappear" id="publication_toappear">
		</div>


		<div id="publication_doi_number-container" class="field f_100">
			<label for="publication_doi_number">
				DOI
			</label>
			<input type="text" name="publication_doi_number" id="publication_doi_number">
		</div>

		<div id="publication_url-container" class="field f_100">
			<label for="publication_url">
				URL
			</label>
			<input type="text" name="publication_url" id="publication_url">
		</div>

		<div id="publication_school-container" class="field f_100">
			<label for="publication_school">
				School
			</label>
			<input type="text" name="publication_school" id="publication_school">
		</div>

		<div id="publication_institution-container" class="field f_100">
			<label for="publication_institution">
				Institution
			</label>
			<input type="text" name="publication_institution" id="publication_institution">
		</div>

		<div id="publication_organization-container" class="field f_100">
			<label for="publication_organization">
				Organization
			</label>
			<input type="text" name="publication_organization" id="publication_organization">
		</div>

		<div id="publication_journal-container" class="field f_100">
			<label for="publication_journal">
				Journal
			</label>
			<input type="text" name="publication_journal" id="publication_journal">
		</div>

		<div id="publication_booktitle-container" class="field f_100">
			<label for="publication_booktitle">
				Book title
			</label>
			<input type="text" name="publication_booktitle" id="publication_booktitle">
		</div>

		<div id="publication_edition-container" class="field f_100">
			<label for="publication_edition">
				Edition
			</label>
			<input type="text" name="publication_edition" id="publication_edition">
		</div>

		<div id="publication_pages-container" class="field f_100">
			<label for="publication_pages">
				Pages
			</label>
			<input type="text" name="publication_pages" id="publication_pages">
		</div>

		<div id="publication_issuenum-container" class="field f_100">
			<label for="publication_issuenum">
				Issue number
			</label>
			<input type="text" name="publication_issuenum" id="publication_issuenum">
		</div>

		<div id="publication_volume-container" class="field f_100">
			<label for="publication_volume">
				Volume
			</label>
			<input type="text" name="publication_volume" id="publication_volume">
		</div>


		<div id="publication_chapter-container" class="field f_100">
			<label for="publication_chapter">
				Chapter
			</label>
			<input type="text" name="publication_chapter" id="publication_chapter">
		</div>

		<div id="publication_technumber-container" class="field f_100">
			<label for="publication_technumber">
				Tech number
			</label>
			<input type="text" name="publication_technumber" id="publication_technumber">
		</div>

		<div id="publication_series-container" class="field f_100">
			<label for="publication_series">
				Series
			</label>
			<input type="text" name="publication_series" id="publication_series">
		</div>

		<div id="publication_isbn-container" class="field f_100">
			<label for="publication_isbn">
				ISBN
			</label>
			<input type="text" name="publication_isbn" id="publication_isbn">
		</div>

		<div id="publication_address-container" class="field f_100">
			<label for="publication_address">
				Address
			</label>
			<input type="text" name="publication_address" id="publication_address">
		</div>

		<div id="publication_howpublished-container" class="field f_100">
			<label for="publication_howpublished">
				How published
			</label>
			<input type="text" name="publication_howpublished" id="publication_howpublished">
		</div>

		<div id="publication_publisher-container" class="field f_100">
			<label for="publication_publisher">
				Publisher
			</label>
			<input type="text" name="publication_publisher" id="publication_publisher">
		</div>

		<div id="publication_note-container" class="field f_100">
			<label for="publication_note">
				Note
			</label>
			<input type="text" name="publication_note" id="publication_note">
		</div>

		<div id="publication_abstract-container" class="field f_100">
			<label for="publication_abstract">
				Abstract
			</label>
			<textarea name="publication_abstract" id="publication_abstract"></textarea>
		</div>

		<div id="form-submit" class="field f_100 clearfix submit">
			<input type="submit" value="Submit">
		</div>

	</form>
</div>

<br clear="all" />

{*---------------------------------------------------------------------------*}
