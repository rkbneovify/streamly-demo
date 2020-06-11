//
//  PrivacyData.swift
//  Streamly
//
//  Created by Rohit Singh on 6/11/20.
//  Copyright © 2020 Rohit Singh. All rights reserved.
//

import Foundation

struct PrivacyResponseData : Codable {

    let response_code : Int?
    let success : Bool?
    let data : String?

    enum CodingKeys: String, CodingKey {

        case response_code = "response_code"
        case success = "success"
        case data = "data"
    }

    init(from decoder: Decoder) throws {
        let values = try decoder.container(keyedBy: CodingKeys.self)
        response_code = try values.decodeIfPresent(Int.self, forKey: .response_code)
        success = try values.decodeIfPresent(Bool.self, forKey: .success)
        data = try values.decodeIfPresent(String.self, forKey: .data)

    }
}
