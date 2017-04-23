namespace php wineMateThrift
namespace java com.zhaoyuzhang.winemateinternaltool.thriftfiles

struct TagInfo {
	1: string tagID,
	2: string tagPassword,
	3: string authenticationKey
	4: i32 wineID,
	5: i32 rollNumber,
	6: string operatorID,
}

enum UploadTagInfoStatus {
	UPLOAD_SUCCESS = 1,
	UPLOAD_DUPLICATE_TAG_ID = 2,
	UPLOAD_FAILED = 3,
}

struct UploadTagInfoResponse {
	1: UploadTagInfoStatus status,
	2: TagInfo tagInfo, // only set when status == UPLOAD_DUPLICATE_TAG_ID;
}


service WineMateServices {
	UploadTagInfoResponse uploadTagInfo(1: TagInfo tagInfo);
	TagInfo getTagInfo(1: string tagID);
}

