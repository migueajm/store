import { actionColumn, dateColumn } from "../utilities/custom_columns.js";

export const columns = [
	{data: "id"},
	{data: "name"},
	{data: "description"},
	dateColumn.createdAt,
	dateColumn.updatedAt,
	actionColumn
];