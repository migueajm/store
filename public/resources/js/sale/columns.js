import { actionColumn, dateColumn } from "../utilities/custom_columns.js";

export const columns = [
	{data: "id"},
	{data: "total_amount"},
	dateColumn.saleAt,
	{data: "payment_method"},
	{data: "user_name"},
	actionColumn
];