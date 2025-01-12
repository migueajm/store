import { dateColumn } from "../utilities/custom_columns.js";

export const columns = [
	{data: "id"},
	{data: "product"},
	{data: "quantityChange"},
	{data: "reason"},
	dateColumn.createdAt
];